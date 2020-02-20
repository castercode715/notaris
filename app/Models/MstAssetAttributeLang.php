<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAssetAttributeLang extends Model
{
    protected $table = 'mst_asset_attribute_lang';

    public $timestamps = false;

    protected $fillable = [
    	'asset_attribute_id',
    	'code',
    	'description',
    ];

    public function attribute()
    {
        return $this->belongsTo('App\models\MstAssetAttribute');
    }
}
