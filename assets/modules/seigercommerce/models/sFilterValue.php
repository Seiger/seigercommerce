<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sFilterValue extends Eloquent\Model
{
    protected $primaryKey = 'vid';

    /**
     * Get the filter that owns the value.
     */
    public function getFilter()
    {
        return $this->belongsTo(sFilter::class, 'filter')
            ->leftJoin('s_filter_translates', 's_filters.id', '=', 's_filter_translates.filter')
            ->whereLang(evo()->getConfig('lang', 'uk'));
    }

    /**
     * Get the translates for the filter.
     */
    public function texts()
    {
        return $this->hasMany(sFilterTranslate::class, 'filter', 'filter');
    }
}