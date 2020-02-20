<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAssetAttrValue extends Model
{
    protected $table = 'mst_asset_attr_value';

    protected $fillable = [
    	'asset_id',
    	'attr_asset_id',
    	'value',
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_at',
    	'deleted_by',
    	'is_deleted'
    ];
}
