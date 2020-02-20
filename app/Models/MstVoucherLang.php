<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstVoucherLang extends Model
{
    public $timestamps = false;
    protected $table = 'mst_voucher_lang';
    protected $fillable = ['voucher_id','code','title','desc','image','iframe'];
}
