<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class VABCAInquiry extends Model
{
    protected $table = 'sys_bca_payment_confirmation';

    public $timestamps = false;

    public $incrementing = false;

    public function widgetValue($status)
    {
        $query = "
            SELECT 
                COUNT(*) AS total
            FROM
                sys_bca_payment_confirmation
            WHERE
            	payment_flag_status = '".$status."'
            AND date(created_at) = current_date()
        ";
    	return DB::select($query)[0]->total;
        /*AND date BETWEEN CONCAT(SUBDATE(CURRENT_DATE(), 1),' ','21:00:00') 
        AND CONCAT(CURRENT_DATE(), ' ', '21:00:00');*/
    }

    public function detail($id)
    {
        return DB::table('sys_bca_inquiry')->find($id);
    	// return DB::select($query)[0];
    }
}
