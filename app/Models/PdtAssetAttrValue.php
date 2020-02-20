<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdtAssetAttrValue extends Model
{
    protected $table = 'pdt_asset_attr_value';

    protected $fillable = ['asset_id','attr_asset_id','value','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
}
