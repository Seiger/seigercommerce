<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sFilterValue extends Eloquent\Model
{
    protected $primaryKey = 'vid';

    /**
     * Get the filter that owns the value.
     */
    public function filter()
    {
        return $this->belongsTo(sFilter::class, 'filter');
    }
}