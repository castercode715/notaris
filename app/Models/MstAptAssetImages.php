<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAptAssetImages extends Model
{
	protected $primaryKey = 'code_apt';
    protected $keyType = 'string'; 
    protected $increments = false;
    
    protected $table = 'mst_apt_asset_img';
    protected $fillable = ['code_apt','image','featured'];
    public $timestamps = false;
}
