<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdtAssetAttribute extends Model
{
    protected $table = 'pdt_asset_attribute';

    protected $fillable = ['category_asset_id','name','active','created_by','updated_by','deleted_at','deleted_by','is_deleted'];
}
