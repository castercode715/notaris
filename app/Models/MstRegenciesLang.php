<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRegenciesLang extends Model
{
    protected $table = 'mst_regencies_lang';

    protected $fillable = [
    	'regencies_id',
    	'code',
    	'name'
    ];

    public $timestamps = false;

    public function regencies()
    {
    	return $this->belongsTo('App\Models\MstRegencies');
    }
}
