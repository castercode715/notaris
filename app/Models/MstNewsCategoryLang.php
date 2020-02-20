<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsCategoryLang extends Model
{
    protected $table = 'mst_news_category_lang';
    public $timestamps = false;
    protected $fillable = [
    	'category_id',
    	'code',
    	'name'
    ];
}
