<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrcKprModels extends Model
{
	protected $primaryKey = 'code';
    protected $keyType = 'string'; 
    protected $increments = false;

    protected $table = 'trc_kpr';
    protected $fillable = ['app_number','code_kpr','investor_id','price','tenor','installment','booked_date','installment_start_date','installment_end_date','bill_issued_date','status','created_by','updated_by','is_deleted','deleted_at','deleted_by'];
}
