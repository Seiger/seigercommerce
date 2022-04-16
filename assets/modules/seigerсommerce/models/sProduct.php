<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sProduct extends Eloquent\Model
{
    /**
     * Get the translates for the product.
     */
    public function texts()
    {
        return $this->hasMany(sProductTranslate::class, 'product', 'product');
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
        $base_url = evo()->getConfig('base_url', '/');
        if ($base_url == '/') {
            $base_url = MODX_SITE_URL;
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
        $base_url = evo()->getConfig('base_url', MODX_SITE_URL);
        $id = (isset($this->product) && (int)$this->product > 0) ? $this->product : $this->id;

        if (!empty($this->cover) && is_file(MODX_BASE_PATH . 'assets/images/products/' . $id . '/' . $this->cover)) {
            $coverSrc = rtrim($base_url, '/') . '/assets/images/products/' . $id . '/' . $this->cover;
        } else {
            $coverSrc = rtrim($base_url, '/') . '/assets/modules/seigerсommerce/images/noimage.png';
        }

        return $coverSrc;
    }
}