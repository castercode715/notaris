<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MstAsset;

class MstAssetLang extends Model
{
    protected $table = 'mst_asset_lang';

    public $timestamps = false;

    protected $fillable = [
    	'asset_id',
    	'code',
    	'asset_name',
    	'description',
    	'file_resume',
    	'file_fiducia'
    ];

    public function isLanguageComplete()
    {
        $model = MstAsset::findOrFail($this->asset_id);
        return $model->isLanguageComplete();
    }
}
