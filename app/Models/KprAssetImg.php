<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KprAssetImg extends Model
{
    protected $table = 'mst_kpr_asset_img';
    protected $fillable = ['code_kpr','image','featured'];
    public $timestamps = false;
    
    public function kprAsset()
    {
        return $this->belongsTo('App\Models\KprAsset', 'code_kpr');
    }
}
