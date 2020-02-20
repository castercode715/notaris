<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstBannerLang extends Model
{
    protected $table = 'mst_banner_lang';

    public $timestamps = false;

    protected $fillable = [
    	'banner_id',
    	'code',
    	'title',
    	'sub_title',
    	'description',
    	'image',
    	'iframe'
    ];
}
