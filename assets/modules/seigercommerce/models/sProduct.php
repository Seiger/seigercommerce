<?php namespace sCommerce\Models;

use EvolutionCMS\Facades\UrlProcessor;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;

class sProduct extends Eloquent\Model
{
    /**
     * Availability constants
     */
    const AVAILABILITY_NOT_AVAILABLE = 0;
    const AVAILABILITY_IN_STOCK = 1;
    const AVAILABILITY_ON_ORDER = 2;

    /**
     * Type constants
     */
    const TYPE_SIMPLE = 0;
    const TYPE_OPTIONAL = 1;
    const TYPE_VARIABLE = 2;

    /**
     * Status constants
     */
    const STATUS_SELECT = 0;
    const STATUS_HIT = 1;
    const STATUS_NEW = 2;
    const STATUS_TOP = 3;
    const STATUS_SALE = 4;
    const STATUS_STOCK = 5;

    /**
     * Return list of availability codes and labels
     *
     * @return array
     */
    public static function listAvailability(): array
    {
        $list = [];
        global $_lang;
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $constant => $value) {
            if (str_starts_with($constant, 'AVAILABILITY_')) {
                $const = strtolower(str_replace('AVAILABILITY_', '', $constant));
                $list[$value] = ($_lang['scommerce_'.$const] ?? $const);
            }
        }
        return $list;
    }

    /**
     * Return list of type codes and labels
     *
     * @return array
     */
    public static function listType(): array
    {
        $list = [];
        global $_lang;
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $constant => $value) {
            if (str_starts_with($constant, 'TYPE_')) {
                $const = strtolower($constant);
                $list[$value] = ($_lang['scommerce_'.$const] ?? $const);
            }
        }
        return $list;
    }

    /**
     * Return list of type codes and labels
     *
     * @return array
     */
    public static function listStatus(): array
    {
        $list = [];
        global $_lang;
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $constant => $value) {
            if (str_starts_with($constant, 'STATUS_')) {
                $const = strtolower($constant);
                $list[$value] = ($_lang['scommerce_'.$const] ?? $const);
            }
        }
        return $list;
    }

    /**
     * Get the translates for the product.
     */
    public function texts()
    {
        return $this->hasMany(sProductTranslate::class, 'product', 'product');
    }

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(sCategory::class, 's_product_category', 'product', 'category', 'product');
    }

    /**
     * Get the features for the product.
     */
    public function features()
    {
        return $this
            ->belongsToMany(sFilterValue::class, 's_product_features', 'product', 'feature', 'product')
            ->leftJoin('s_filters', 's_filters.id', '=', 's_filter_values.filter')
            ->orderBy('s_filters.position')
            ->orderBy('s_filter_values.position');
    }

    /**
     * Get the product item with lang
     *
     * @param $query
     * @param $locale
     * @return mixed
     */
    public function scopeLang($query, $locale)
    {
        return $this->leftJoin('s_product_translates', function ($leftJoin) use ($locale) {
            $leftJoin->on('s_products.id', '=', 's_product_translates.product')
                ->where('lang', function ($leftJoin) use ($locale) {
                    $leftJoin->select('lang')
                        ->from('s_product_translates')
                        ->whereRaw(DB::getTablePrefix().'s_product_translates.product = '.DB::getTablePrefix().'s_products.id')
                        ->whereIn('lang', [$locale, 'base'])
                        ->orderByRaw('FIELD(lang, "'.$locale.'", "base")')
                        ->limit(1);
                });
        });
    }

    /**
     * Filter search
     *
     * @return mixed
     */
    public function scopeSearchProducts()
    {
        if (request()->has('search')) {
            $fields = collect(['code', 'pagetitle', 'introtext', 'content']);

            $search = Str::of(request('search'))
                ->stripTags()
                ->replaceMatches('/[^\p{L}\p{N}\@\.!#$%&\'*+-\/=?^_`{|}~]/iu', ' ') // allowed symbol in email
                ->replaceMatches('/(\s){2,}/', '$1') // removing extra spaces
                ->trim()->explode(' ')
                ->filter(fn($word) => mb_strlen($word) > 2);

            $select = collect([0]);

            $search->map(fn($word) => $fields->map(fn($field) => $select->push("(CASE WHEN \"{$field}\" LIKE '%{$word}%' THEN 1 ELSE 0 END)"))); // Generate points source

            return $this->select('*', DB::Raw('(' . $select->implode(' + ') . ') as points'))
                ->when($search->count(), fn($query) => $query->where(fn($query) => $search->map(fn($word) => $fields->map(fn($field) => $query->orWhere($field, 'like', "%{$word}%")))))
                ->leftJoin('s_product_translates', 's_products.id', '=', 's_product_translates.product')->where('lang', '=', evo()->getConfig('lang', 'en'))
                ->orderByDesc('points');
        }
    }

    /**
     * Get the product link
     *
     * @return string link
     */
    public function getLinkAttribute()
    {
        if ($this->category) {
            $catalog_root = $this->category;
        } else {
            $site_start = evo()->getConfig('site_start', 1);
            $catalog_root = evo()->getConfig('catalog_root', $site_start);
        }
        $base_url = UrlProcessor::makeUrl(evo()->getConfig('site_start', 1));
        $suffix_url = evo()->getConfig('friendly_url_suffix', '');
        $link = $base_url . $this->alias . $suffix_url;
        return $link;
    }

    /**
     * Get the product full link
     *
     * @return string full_link
     */
    public function getFullLinkAttribute()
    {
        if ($this->category) {
            $catalog_root = $this->category;
        } else {
            $site_start = evo()->getConfig('site_start', 1);
            $catalog_root = evo()->getConfig('catalog_root', $site_start);
        }
        $base_url = UrlProcessor::makeUrl(evo()->getConfig('site_start', 1));
        if (str_starts_with($base_url, '/')) {
            $base_url = MODX_SITE_URL . ltrim($base_url, '/');
        }
        $suffix_url = evo()->getConfig('friendly_url_suffix', '');
        $link = $base_url . $this->alias . $suffix_url;
        return $link;
    }

    /**
     * Get the product cover src link
     *
     * @return string cover_src
     */
    public function getCoverSrcAttribute()
    {
        if (!empty($this->cover) && is_file(MODX_BASE_PATH . $this->cover)) {
            $coverSrc = MODX_SITE_URL . $this->cover;
        } else {
            $coverSrc = MODX_SITE_URL . 'assets/modules/seigercommerce/images/noimage.png';
        }

        return $coverSrc;
    }

    /**
     * Get the product variations group array
     *
     * @return array grouping_parameters_array
     */
    public function getGroupingParametersArrayAttribute(): array
    {
        if ($result = data_is_json($this->grouping_parameters, true)) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * Get the product variations array
     *
     * @return array variations_array
     */
    public function getVariationsArrayAttribute(): array
    {
        if ($result = data_is_json($this->variations, true)) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * Get the product brand
     *
     * @return string brand
     */
    public function getBrandAttribute(): string
    {
        $filterBand = sFilter::whereAlias('brand')->first();
        if ($filterBand) {
            $brand = $this->features->filter(function ($item) use ($filterBand) {
                return $item->filter == $filterBand->id;
            })->first();
        }

        return $brand->{evo()->getConfig('lang', 'base')} ?? '';
    }
}