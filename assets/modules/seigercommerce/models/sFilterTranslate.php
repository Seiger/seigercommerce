<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sFilterTranslate extends Eloquent\Model
{
    protected $fillable = ['filter', 'lang', 'pagetitle', 'introtext', 'content', 'seotitle', 'seodescription'];
}