<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrcTransactionBalanceOutStatus extends Model
{
    protected $table = "trc_transaction_balance_out_status";

    protected $fillable = [
    	'transaction_balance_out_id',
    	'response',
    	'status',
    	'information',
    	'created_at'
    ];
}
