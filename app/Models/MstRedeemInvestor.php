<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRedeemInvestor extends Model
{
    protected $table = 'mst_redeem_investor';
    public $fillable = ['investor_id','redeem_voucher_id','used'];
    protected $primaryKey = null;
    public $incrementing = false; 
    public $timestamps = false;
}
