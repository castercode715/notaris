<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdtAssetPhoto extends Model
{
    protected $table = 'pdt_asset_photo';

    protected $fillable = ['asset_id','photo_uri','desc','featured','active',
    						'created_by','updated_by','deleted_date',
    						'deleted_by','is_deleted'];
}
