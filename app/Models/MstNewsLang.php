<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsLang extends Model
{
    protected $table = 'mst_news_lang';

    protected $fillable = [
    	'news_id',
    	'code',
    	'title',
    	'sub_title',
    	'description',
    	'image',
    	'iframe'
    ];
    
    public $timestamps = false;
}
