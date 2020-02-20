<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrcSaldo extends Model
{
	public $timestamps = false;
	
    protected $table = "trc_saldo";

    protected $fillable = ['transaction_asset_id','transaction_saldo_id','date',
    					'credit','debit','information','active',
    					'deleted_by','deleted_at','is_deleted'];
}
