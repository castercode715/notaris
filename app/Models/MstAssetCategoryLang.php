<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAssetCategoryLang extends Model
{
    protected $table = 'mst_asset_category_lang';

    public $timestamps = false;

    protected $fillable = [
    	'asset_category_id',
    	'code',
    	'description'
    ];
}
