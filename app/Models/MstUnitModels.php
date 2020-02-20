<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstUnitModels extends Model
{
    protected $primaryKey = 'code_unit';
    protected $keyType = 'string'; 
    protected $increments = false;
    public $timestamps = false;

    protected $table = 'mst_unit_apt';

    public function floor()
    {
        return $this->belongsTo('App\Models\MstFloorApt', 'code_floor');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\AptBooking');
    }

    protected $fillable = ['code_unit','name','code_floor','is_deleted'];

}
