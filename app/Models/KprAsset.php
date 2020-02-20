<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KprAsset extends Model
{
    protected $table = 'mst_kpr_asset';
    
    protected $fillable = ['code','name','location','price','tenor','installment','description','term_cond','status','created_by','updated_by','booked_at','booked_by','is_deleted','deleted_at','deleted_by'];
    
    protected $primaryKey = 'code';
    
    protected $keyType = 'string'; 
    
    protected $increments = false;
    
    // public $status = [
    //     'D' => ['name'=>'Draft', 'label'=>'label-warning'],
    //     'P' => ['name'=>'Published', 'label'=>'label-success'],
    //     'U' => ['name'=>'Unpublished', 'label'=>'label-danger']
    // ];

    public function img()
    {
        return $this->hasMany('App\Models\KprAssetImg');
    }

    public function employeeC()
    {
        $this->belongsTo('App\Models\MstEmployee','created_by');
    }

    public function employeeU()
    {
        $this->belongsTo('App\Models\MstEmployee','updated_by');
    }

    public function booked()
    {
        $this->belongsTo('App\Models\MstEmployee','booked_by');
    }
}
