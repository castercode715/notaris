<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAssetAttribute extends Model
{
    protected $table = 'mst_asset_attribute';

    protected $fillable = [
    	'category_asset_id',
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_at',
    	'deleted_by',
    	'is_deleted'
    ];

    public function category()
    {
        return $this->belongsTo('App\models\MstAssetCategory');
    }

    public function attribute()
    {
        return $this->hasMany('App\models\MstAssetAttributeLang');
    }
}
