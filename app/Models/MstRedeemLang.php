<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRedeemLang extends Model
{
    protected $table = 'mst_redeem_lang';
    public $fillable = ['redeem_voucher_id','code','title','description','image'];
    public $timestamps = false;
}
