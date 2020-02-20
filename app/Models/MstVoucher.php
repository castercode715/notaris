<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstVoucher extends Model
{
    protected $table = 'mst_voucher';
    protected $fillable = ['asset_id','title','desc','image','iframe','date_start','date_end','long_promo','number_interest','quota','status','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
}
