<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstDistricts extends Model
{
    protected $table = 'mst_districts';

    public $timestamps = false;

	protected $primaryKey = 'id';
	
	public $incrementing = false;

	protected $fillable = [
		'id',
		'regencies_id',
		'name'
	];
}
