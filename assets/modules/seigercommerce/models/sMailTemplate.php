<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sMailTemplate extends Eloquent\Model
{
    /**
     * Get the mail template item with lang
     *
     * @param $query
     * @param $locale
     * @return mixed
     */
    public function scopeLang($query, $locale)
    {
        return $this->where('lang', '=', $locale);
    }
}