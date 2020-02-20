<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstCountries extends Model
{
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = false;
    protected $table = 'mst_countries';
    protected $fillable = ['name','language_code','currency_code'];
    public $keyType = 'string';
}
