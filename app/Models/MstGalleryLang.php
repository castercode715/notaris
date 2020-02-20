<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstGalleryLang extends Model
{
    protected $table = 'mst_gallery_lang';

    public $timestamps = false;

    protected $fillable = [
    	'gallery_id',
    	'code',
    	'title',
    	'description',
    ];
}
