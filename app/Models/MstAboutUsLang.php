<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAboutUsLang extends Model
{
    public $timestamps = false;
    protected $table = 'mst_about_us_lang';
    protected $fillable = [
    	'about_us_id',
    	'code',
    	'title',
    	'description',
    	'image',
    	'iframe'
    ];
}
