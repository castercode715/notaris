<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MNotarisModels extends Model
{
    protected $table = 'm_notaris';
    protected $fillable = [
        'name',
        'no_rek',
      	'atas_nama',
      	'bank_name',
      	'information',
        'created_by',
      	'updated_by',
      	'deleted_date',
      	'deleted_by',
      	'is_deleted'
    ];

    public function getNotarisValue($id)
    {
    	$result = DB::table('m_notaris as a')
    			->where('a.is_deleted', 0)
    			->where('a.id', $id)
    			->count();

    	return $result;
    }
}
