<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdtAssetRating extends Model
{
    protected $table = 'pdt_asset_rating';

    protected $fillable = ['investor_id','asset_id','rating','review','active','created_by','updated_by','deleted_date',
    						'deleted_by','is_deleted'];
}
