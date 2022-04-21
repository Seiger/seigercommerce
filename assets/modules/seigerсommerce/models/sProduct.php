<?php namespace sCommerce\Models;

use EvolutionCMS\Facades\UrlProcessor;
use Illuminate\Database\Eloquent;
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
     * Get the product item with lang
     *
     * @param $query
     * @param $locale
     * @return mixed
     */
    public function scopeLang($query, $locale)
    {
        return $this->leftJoin('s_product_translates', 's_products.id', '=', 's_product_translates.product')->where('lang', '=', $locale);
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

    /**
     * Get the product cover src link
     *
     * @return string
     */
    public function getCoverSrcAttribute()
    {
        $site_start = evo()->getConfig('site_start', 1);
        $base_url = UrlProcessor::makeUrl($site_start);
        if (str_starts_with($base_url, '/')) {
            $base_url = MODX_SITE_URL . ltrim($base_url, '/');
        }
        if (!empty($this->cover) && is_file(MODX_BASE_PATH . $this->cover)) {
            $coverSrc = $base_url . $this->cover;
        } else {
            $coverSrc = $base_url . 'assets/modules/seigerсommerce/images/noimage.png';
        }

        return $coverSrc;
    }
}