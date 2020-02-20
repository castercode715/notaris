<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAssetCategory extends Model
{
    protected $table = 'mst_asset_category';

    protected $fillable = [
    	// 'desc', 
    	'active', 
    	'created_by', 
    	'updated_by', 
    	'deleted_at', 
    	'deleted_by', 
    	'is_deleted'
    ]; 

    public function category()
    {
        return $this->hasMany('App\models\MstAssetCategoryLang');
    }

    public function attribute()
    {
        return $this->hasMany('App\models\MstAssetAttributeLang');
    }
}
