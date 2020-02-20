<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class VTranInvestorCashout extends Model
{
    protected $table = 'v_tran_investor_cashout';

    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    public function countBalanceOut($status)
    {
        $date = date('Y-m-d');
        
           
    	return DB::select("
            SELECT 
                COUNT(*) as total
            FROM trc_transaction_balance_out AS a
            LEFT JOIN mst_investor as i on i.id = a.investor_id
            LEFT JOIN trc_inv_bank as ib on ib.id = a.inv_bank_id
            LEFT JOIN mst_bank as b on b.id = ib.bank_id
            WHERE 
                a.status = lower('".$status."')
                AND a.information = 'Withdraw Balance'
                AND a.is_deleted = 0
                AND left(a.transaction_number, 6) = 'TRS/02'
                AND i.is_dummy = 0
                AND a.date like '%$date%'

    	")[0]->total;

    }
}
