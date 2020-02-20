<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MstRedeemLang;
use Illuminate\Support\Facades\DB;

class MstRedeemVoucher extends Model
{
    protected $table = 'mst_redeem_voucher';
    public $fillable = ['code_redeem','amount','quota','remain_quota','status','type','date_start','date_end','created_by','updated_by','is_deleted'];

    public function employeeCreate()
    {
        return $this->belongsTo('App\Models\MstEmployee', 'created_by');
    }

    public function employeeUpdate()
    {
        return $this->belongsTo('App\Models\MstEmployee', 'updated_by');
    }

    public function tcRedeem()
    {
        return $this->hasMany('App\Models\MstTCRedeem');
    }

    public function redeemInd()
    {
        return MstRedeemLang::where('code','IND')->where('redeem_voucher_id', $this->id)->first();
    }

    public function otherLang($lang)
    {
        return MstRedeemLang::where('code', $lang)->where('redeem_voucher_id',$this->id)->first();
    }

    public function allowToPublish()
    {
        $totalLanguage = DB::table('mst_language')->count();
        
        $totalVoucherLang = DB::table('mst_redeem_lang')
                            ->where('redeem_voucher_id', $this->id)
                            ->count();
        $total = $totalLanguage == $totalVoucherLang ? true : false;
        return $this->status == 'DRAFT' && $total ? true : false;
    }
}
