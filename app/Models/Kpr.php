<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kpr extends Model
{
    protected $table = 'trc_kpr';
    protected $fillable = ['app_number','code_kpr','investor_id','price','tenor','installment','booked_date','installment_start_date','installment_end_date','bill_issued_date','status','created_by','updated_by'];
    protected $primaryKey = 'app_number';
    protected $keyType = 'string'; 
    protected $increments = false;

    public function investor()
    {
        $this->belongsTo('App\Models\MstInvestor');
    }

    public function asset()
    {
        $this->belongsTo('App\Models\KprAsset','code_kpr');
    }

    public function installment()
    {
        $this->hasMany('App\Models\KprInstallment');
    }

    public function employeeC()
    {
        $this->belongsTo('App\Models\MstEmployee','created_by');
    }

    public function employeeU()
    {
        $this->belongsTo('App\Models\MstEmployee','updated_by');
    }

    public static function appNumber()
    {
        return DB::select('select f_kpr_app_number() as app_number')[0]->app_number;
    }
}
