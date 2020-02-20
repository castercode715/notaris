<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRegencies extends Model
{
    protected $table = 'mst_regencies';

    protected $fillable = [
    	'id',
    	'provinces_id'
    ];

    public $incrementing = false;

    public $primaryKey = 'id';

    public $keyType = 'string';

    public $timestamps = false;

    public function province()
    {
    	return $this->belongsTo('App\Models\MstProvinces');
    }

    public function regenciesLang()
    {
    	return $this->hasMany('App\Models\MstRegenciesLang');
    }
}
