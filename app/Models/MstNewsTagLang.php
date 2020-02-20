<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsTagLang extends Model
{
    protected $table = 'mst_news_tag_lang';

    protected $fillable = [
    	'tag_id',
    	'code',
    	'description'
    ];

    public $timestamps = false;
}
