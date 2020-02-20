<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAssetPhoto extends Model
{
    protected $table = 'mst_asset_photo';

    protected $fillable = ['asset_id','photo','desc','featured','active',
    						'created_by','updated_by','deleted_date',
    						'deleted_by','is_deleted'];
}
