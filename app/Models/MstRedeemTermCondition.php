<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRedeemTermCondition extends Model
{
    // tes git aja
    protected $table = 'mst_redeem_term_conditions';
    public $fillable = ['code','label','min_amount','min_tenor','date_start','date_end','created_by','updated_by'];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false; 
    public $timestamps = false;
}
