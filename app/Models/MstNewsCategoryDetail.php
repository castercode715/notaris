<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsCategoryDetail extends Model
{
    protected $table = 'mst_news_category_detail';

    protected $fillable = [
    	'news_id',
    	'category_id',
    	'active'
    ];
}
