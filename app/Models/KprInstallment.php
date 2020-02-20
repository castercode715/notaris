<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KprInstallment extends Model
{
    protected $table = 'trc_kpr_installment';
    protected $fillable = ['app_number','installment_number','bill_due_date','bill_amount','payment_date','payment_channel','created_at'];
    protected $primaryKey = null;
    protected $increments = false;
    public $timestamps = false;

    public function kpr()
    {
        $this->belongsTo('App\Models\TrcKpr','app_number');
    }
}
