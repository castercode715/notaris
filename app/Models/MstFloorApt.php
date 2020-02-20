<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstFloorApt extends Model
{
    protected $table = 'mst_floor_apt';
    public $increments = false;
    protected $primaryKey = 'code_floor';
    public $timestamps = false;

    public function apt()
    {
        return $this->belongsTo('App\Models\MstAptAssetModels', 'code_apt');
    }

    public function units()
    {
        return $this->hasMany('App\Models\MstUnitModels');
    }
}
