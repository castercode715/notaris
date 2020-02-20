<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrcRedeem extends Model
{
    protected $table = 'trc_redeem';
    public $fillable = ['redeem_voucher_id','investor_id','redeem_date','amount'];
}
