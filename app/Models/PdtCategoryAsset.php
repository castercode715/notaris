<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdtCategoryAsset extends Model
{
    protected $table = 'pdt_category_asset';

    protected $fillable = ['desc', 'active', 'created_by', 'updated_by', 'deleted_date', 'deleted_by', 'is_deleted']; 
}
