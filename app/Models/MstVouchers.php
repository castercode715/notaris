<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstVouchers extends Model
{
    protected $table = 'mst_voucher2';

    protected $fillable = [
    	'code',
    	'asset_id',
    	'date_start',
    	'date_end',
    	'time_of_use',
    	'value_type',
    	'value',
    	'quota',
        'remain_quota',
    	'min_invest_amount',
    	'type',
    	'status',
    	'created_by',
    	'updated_by',
    ];

    public function getData($code)
    {
        $data = DB::table('mst_voucher_lang')
                ->where('code',$code)
                ->where('voucher_id',$this->id)
                ->first();
        return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $partner = DB::table('mst_voucher2 as h')
                ->join('mst_voucher_lang as hl','h.id','hl.voucher_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $partner)
            $result = false;

        return $result;
    }

    public function isLangComplete($id)
    {
        $totalLanguage = DB::table('mst_language')->count();
        
        $totalVoucherLang = DB::table('mst_voucher_lang')
                            ->where('voucher_id', $id)
                            ->count();

        $result = false;
        if($totalLanguage == $totalVoucherLang)
            $result = true;

        return $result;
    }

    public function sendVoucherNotification($id)
    {
        $model = DB::statement('call p_voucher_send_notification(?, @vret)', [$id]);
        $return = DB::select('select @vret as result')[0]->result;
        $result = explode('|', $return);
        $status = true;
        if($result[0] != '0000')
            $status = false;
        return $status;
    }
}
