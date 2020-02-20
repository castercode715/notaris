<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Utility;

class TrcServiceModels extends Model
{
    protected $table = 't_services';
    protected $fillable = [
    	'client_id',
    	'debitur_id',
    	'bank_id',
    	'project_name',
    	'fsk_no',
    	'so_no',
    	'invoice_no',
    	'fsk_date',
    	'so_date',
    	'invoice_date',
    	'pic',
    	'para_pihak',
    	'catatan_dokumen',
    	'discount',
    	'grand_total',
    	'created_by',
    	'updated_by',
    	'is_deleted',
    	'deleted_at',
    	'deleted_by'
    ];

    public function Squance()
    {
        $uti = new Utility();
        $notaris = $uti->getUserNew();

        if ($notaris == 1) {
            $flag = "YW";
        }else if($notaris == 2){
            $flag = "HG";
        }else{
            $flag = "";
        }

        $query = DB::select("SELECT max(fsk_no) as maxCode FROM t_services WHERE is_deleted = 0 AND fsk_no like '%$flag%'");

        foreach ($query as $key ) {
            $urut = $key->maxCode;
        }

        $noUrut = (int) substr($urut, -4, 4);
        $noUrut++;

        $code = "FSK/".$flag."/".date('Y')."/".sprintf("%04s", $noUrut);
        return $code;
    }
}
