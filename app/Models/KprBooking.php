<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KprBooking extends Model
{
    protected $table = 'trc_kpr_booking';
    protected $fillable = ['investor_id','code_kpr','surveyor','surveyor_phone','survey_start_date','survey_end_date','note','status','updated_by','price','installment','tenor','canceled_at','canceled_by','approved_at','approved_by','rejected_at','rejected_by'];

    public $list_status = [
        'NEW'   => ['name' => 'NEW', 'label' => 'label label-primary'],
        'SURVEY'   => ['name' => 'SURVEY', 'label' => 'label label-warning'],
        'APPROVED'   => ['name' => 'APPROVED', 'label' => 'label label-success'],
        'REJECTED'   => ['name' => 'REJECTED', 'label' => 'label label-danger'],
        'CANCELED'   => ['name' => 'CANCELED', 'label' => 'label bg-purple'],
    ];

    public function investor()
    {
        $this->belongsTo('App\Models\MstInvestor');
    }

    public function asset()
    {
        $this->belongsTo('App\Models\KprAsset','code_kpr');
    }

    public function employee()
    {
        $this->belongsTo('App\Models\MstEmployee','updated_by');
    }

    public function installment()
    {
        $this->hasMany('App\Models\KprInstallment');
    }

    public function statusLabeled()
    {
        $data = $this->list_status[$this->status];
        return '<span class="'.$data['label'].'">'.$data['name'].'</span>';
    }
}
