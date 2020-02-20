<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstContactUs extends Model
{
    protected $table = 'mst_contact';

    protected $fillable = [
    	'parent_id',
    	'investor_id',
    	'asset_id',
    	'flag',
    	'message',
    	'file',
    	'active',
        'created_by',
    	'created_at',
    	'deleted_at',
    	'deleted_by',
    	'is_deleted'
    ];
    public $timestamps = false;

    public function investor()
    {
    	return $this->belongsTo('App\Models\MstInvestor');
    }
}
