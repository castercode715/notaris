<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTCRedeem extends Model
{
    protected $table = 'mst_tc_redeem';
    public $fillable = ['redeem_voucher_id','term_code'];
    protected $primaryKey = null;
    public $incrementing = false; 
    public $timestamps = false;

    public function redeemTermCond()
    {
        return $this->belongsTo('App\Models\MstRedeemTermCondition', 'term_code');
    }

    public function redeemVoucher()
    {
        return $this->belongsTo('App\Models\MstRedeemVoucher', 'redeem_voucher_id');
    }
}
