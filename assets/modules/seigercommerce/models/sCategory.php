<?php namespace sCommerce\Models;

use EvolutionCMS\Models\SiteContent;

class sCategory extends SiteContent
{
    /**
     * The products that belong to the category.
     */
    public function products()
    {
        return $this->belongsToMany(sProduct::class, 's_product_category', 'category', 'product', 'id', 'id');
    }
}