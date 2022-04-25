<?php namespace sCommerce\Models;

use EvolutionCMS\Facades\UrlProcessor;
use Illuminate\Database\Eloquent;
use ReflectionClass;

class sFilter extends Eloquent\Model
{
    /**
     * Type constants
     */
    const FTYPE_CHARACTERISTIC = 0;
    const FTYPE_FILTER = 1;

    /**
     * Type select constants
     */
    const STYPE_NUMBER = 0;
    const STYPE_TEXT = 1;
    const STYPE_SELECT = 2;
    const STYPE_MULTISELECT = 3;

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
            if (str_starts_with($constant, 'FTYPE_')) {
                $const = strtolower($constant);
                $list[$value] = ($_lang['scommerce_'.$const] ?? $const);
            }
        }
        return $list;
    }

    /**
     * Return list of type select codes and labels
     *
     * @return array
     */
    public static function listTypeSelect(): array
    {
        $list = [];
        global $_lang;
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $constant => $value) {
            if (str_starts_with($constant, 'STYPE_')) {
                $const = strtolower($constant);
                $list[$value] = ($_lang['scommerce_'.$const] ?? $const);
            }
        }
        return $list;
    }

    /**
     * Get the translates for the filter.
     */
    public function texts()
    {
        return $this->hasMany(sFilterTranslate::class, 'filter', 'filter');
    }

    /**
     * The categories that belong to the filter.
     */
    public function categories()
    {
        return $this->belongsToMany(sCategory::class, 's_filter_category', 'filter', 'category', 'filter');
    }

    /**
     * Get the filter item with lang
     *
     * @param $query
     * @param $locale
     * @return mixed
     */
    public function scopeLang($query, $locale)
    {
        return $this->leftJoin('s_filter_translates', 's_filters.id', '=', 's_filter_translates.filter')->where('lang', '=', $locale);
    }

    /**
     * Get the product link
     *
     * @return string
     */
    public function getLinkAttribute()
    {
        $site_start = evo()->getConfig('site_start', 1);
        $catalog_root = evo()->getConfig('catalog_root', $site_start);
        $base_url = UrlProcessor::makeUrl($catalog_root);
        if (str_starts_with($base_url, '/')) {
            $base_url = MODX_SITE_URL . ltrim($base_url, '/');
        }
        $suffix_url = evo()->getConfig('friendly_url_suffix', '');
        $link = $base_url . $this->alias . $suffix_url;
        return $link;
    }
}