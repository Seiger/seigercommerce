<?php namespace sCommerce\Models;

use Illuminate\Database\Eloquent;

class sProductTranslate extends Eloquent\Model
{
    protected $fillable = ['pagetitle', 'introtext', 'content', 'seotitle', 'seodescription'];
}