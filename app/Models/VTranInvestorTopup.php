<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrcTransactionBalanceInStatus;
use DB;

class VTranInvestorTopup extends Model
{
    protected $table = 'v_tran_investor_topup';

    public $timestamps = false;

    public $incrementing = false;

    public function countBalanceIn($status)
    {
        $query = "
            SELECT 
                COUNT(*) AS total
            FROM
                trc_transaction_balance_in as bi
            WHERE
                LEFT(bi.transaction_number, 6) = 'TRS/01'
            AND 
                bi.is_deleted = 0
            AND date BETWEEN CONCAT(SUBDATE(CURRENT_DATE(), 1),' ','21:00:00') 
            AND CONCAT(CURRENT_DATE(), ' ', '21:00:00')
        ";

        if($status != '')
            $query .= " AND lower(status) = lower('".$status."');";

    	return DB::select($query)[0]->total;
        /**/
    }

    public function countBalanceInTotal($status)
    {
        $query = "
            SELECT 
                COUNT(*) AS total
            FROM
                trc_transaction_balance_in as bi
            WHERE
                LEFT(bi.transaction_number, 6) = 'TRS/01'
            AND 
                bi.is_deleted = 0
            -- AND date BETWEEN CONCAT(SUBDATE(CURRENT_DATE(), 1),' ','21:00:00') 
            -- AND CONCAT(CURRENT_DATE(), ' ', '21:00:00')
        ";

        if($status != '')
            $query .= " AND lower(status) = lower('".$status."');";

        return DB::select($query)[0]->total;
        /**/
    }

    public function countBalanceInByMethod($method)
    {
        return DB::select("
            SELECT 
                COUNT(*) AS total
            FROM
                trc_transaction_balance_in
            WHERE
                LEFT(transaction_number, 6) = 'TRS/01'
            AND lower(method) = lower('".$method."');
        ")[0]->total;
        /*AND date BETWEEN CONCAT(SUBDATE(CURRENT_DATE(), 1),' ','21:00:00') 
        AND CONCAT(CURRENT_DATE(), ' ', '21:00:00');*/
    }

    public function statusLabel()
    {
        $model = new TrcTransactionBalanceInStatus;
        return $model->statusLabel($this->status);
    }

    public function methodLabel()
    {
        $model = new TrcTransactionBalanceInStatus;
        return $model->methodLabel($this->method);
    }
}
