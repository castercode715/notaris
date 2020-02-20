<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringTopup extends Model
{
    protected $table = 'trc_transaction_balance_in';
    protected $fillable = ['investor_id','trc_asset_investor_id','interest_count','trc_asset_voucher_id','transaction_number','date','amount','information','status','active','created_by_investor','created_by_emp','updated_by_investor','updated_by_emp','deleted_at_emp','deleted_by_emp','is_deleted'];
}
