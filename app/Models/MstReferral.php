<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstReferral extends Model
{
    public $table = 'mst_referral';
    public $fillable = ['investor_id','ref_investor_id','ref_phone_number','amount','info_by','used_date'];
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
