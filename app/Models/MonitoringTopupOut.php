<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringTopupOut extends Model
{
    protected $table = 'trc_transaction_balance_out';
    protected $fillable = ['investor_id','transaction_number','date','amount','information','status','active','created_by_investor','created_by_emp','updated_by_investor','updated_by_emp','deleted_at_emp','deleted_by_emp','is_deleted'];
}
