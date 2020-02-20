<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
use App\Utility;

class InvestorTransactionController extends Controller
{
    public function dataTable($id)
    {
        $model = DB::select("
            SELECT 
                b.id,
                a.date,
                al.asset_name,
                d.full_name,
                b.amount
            FROM
                trc_transaction_asset AS a,
                trc_asset_investor AS b,
                mst_asset as c,
                mst_asset_lang as al,
                mst_investor as d
            WHERE
                b.trc_asset_id = a.id
            and a.investor_id = d.id
            and b.asset_id = c.id
            and c.id = al.asset_id
            AND b.asset_id IN (SELECT 
                    ai.asset_id
                FROM
                    trc_asset_investor AS ai,
                    trc_transaction_asset AS ta
                WHERE
                    ai.trc_asset_id = ta.id
                        AND ta.investor_id = ".$id."
            )
            and b.status = 'ACTIVE' 
            and al.code = 'IND'
            order by
                b.asset_id,
                a.investor_id;
        ");

    	return DataTables::of($model)
    			->editColumn('date', function($model){
                    return date('d-m-Y H:i:s', strtotime($model->date));
                })
    			->editColumn('amount', function($model){
                    $amount = null;
                    if($model->amount != null || $model->amount != '')
                        $amount = "Rp " . number_format($model->amount,0,',','.');
                    return $amount;
                })
    			->addIndexColumn()
                ->rawColumns(['date','amount'])
                ->make(true);
    }

    public function pane()
    {
        return view('transaction.investor.tab.transaction');
    }
}
