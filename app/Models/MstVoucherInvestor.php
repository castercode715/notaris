<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstVoucherInvestor extends Model
{
    protected $table = 'mst_voucher_pair';

    protected $fillable = [
    	'voucher_id',
    	'id',
    	'used'
    ];

    public $timestamps = false;
}
