<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAptAssetModels extends Model
{
    protected $primaryKey = 'code_apt';
    protected $keyType = 'string'; 
    protected $increments = false;

    protected $table = 'mst_apt_asset';
    protected $fillable = [
    	'code_apt',
    	'name',
    	'location',
    	'price',
    	'tenor',
    	'installment',
    	'maintenance',
    	'type_apt',
        'total_unit',
        'remaining_unit',
    	'description',
    	'term_cond',
    	'file',
    	'status',
    	'created_by',
    	'updated_by',
    	'is_deleted',
    	'deleted_at',
    	'deleted_by'
	];
	
	public function floor()
	{
		return $this->hasMany('App\Models\MstFloorApt');
	}

}
