<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sFilterTranslate extends Eloquent\Model
{
    protected $fillable = ['pagetitle', 'introtext', 'content', 'seotitle', 'seodescription'];
}