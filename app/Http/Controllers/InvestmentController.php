<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VInvestmentModels;
use DataTables;
use DB;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = new VInvestmentModels;

        $start = $request->start;
        if($request->start == ""){
            $start = 0;
        }

        $end = $request->end;
        if($request->end == ""){
            $end = 0;
        }

        $filter = $request->filter;
        if($request->filter == ""){
            $filter = 0;
        }

        $cutoff = DB::select("
                SELECT 
                    COUNT(a.id) as cut_off
                    FROM trc_transaction_asset as a
                    JOIN trc_transaction_detail_asset as b on b.trc_asset_id = a.id
                    WHERE a.is_deleted = 0
                    AND b.is_deleted = 0
                    AND a.verified = 'N'
                    -- AND date BETWEEN CONCAT(SUBDATE(CURRENT_DATE(), 1),' ','21:00:00') 
                    --         AND CONCAT(CURRENT_DATE(), ' ', '21:00:00')
            ");


        $cancel = DB::select("
                SELECT 
                    COUNT(a.id) as cancelled
                    FROM trc_transaction_asset as a
                    JOIN trc_transaction_detail_asset as b on b.trc_asset_id = a.id
                    WHERE a.is_deleted = 0
                    AND b.is_deleted = 0
                    AND b.active = 2
                    -- AND date BETWEEN CONCAT(SUBDATE(CURRENT_DATE(), 1),' ','21:00:00') 
                    --         AND CONCAT(CURRENT_DATE(), ' ', '21:00:00')
            ");

        return view('transaction.investment.index', compact(
            'model',
            'cancel',
            'cutoff',
            'start',
            'end',
            'filter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = base64_decode($id);
        $model = DB::table('trc_transaction_asset as a')
                    ->join('mst_investor as b','b.id','=','a.investor_id')
                    ->join('trc_transaction_detail_asset as c','c.trc_asset_id','=','a.id')
                    ->select('a.*','b.full_name','c.amount','a.information','b.is_dummy')
                    ->where('a.id',$id)
                    ->first();
                    

        $data = DB::select("
            SELECT
            
                a.id,
                a.transaction_number,
                a.date,
                d.full_name,
                c.asset_name,
                b.transaction_number_detail,
                b.invest_tenor,
                b.amount,
                b.number_interest,
                b.active as canceled,
                b.canceled_at,
                a.`status`
                
                
                FROM trc_transaction_asset as a
                JOIN trc_transaction_detail_asset as b on b.trc_asset_id = a.id
                JOIN mst_asset_lang as c on c.asset_id = b.asset_id
                JOIN mst_investor as d on d.id = a.investor_id
                
                WHERE c.`code` = 'IND'
                AND a.is_deleted = 0
                AND b.is_deleted = 0
                AND a.id = ".$id."
        ");

        // dd($data);



        return view('transaction.investment.detail', compact([
            'model',
            'data'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dataTable($start = null, $end = null, $filter = null)
    {
        $model = VInvestmentModels::query();
        $model->orderBy('date_transaction','desc');

        if($filter == 'Y'){
            $model = DB::select("
                SELECT
                    `a`.`id` AS `id`,
                    `a`.`date` AS `date_transaction`,
                    `a`.`verified` AS `verified`,
                    a.status,
                    `a`.`currency_code` AS `currency_code`,
                    `a`.`transaction_number` AS `transaction_number`,
                    `c`.`full_name` AS `investor_name`,
                    `c`.`is_dummy` AS `is_dummy`,
                    `a`.`total_amount` AS `total_amount`,
                    (
                    SELECT
                        count( DISTINCT `cox`.`asset_id` ) 
                    FROM
                        `trc_transaction_detail_asset` `cox` 
                    WHERE
                        ( `cox`.`trc_asset_id` = `a`.`id` ) 
                    ) AS `total_asset` 
                FROM
                    (
                        (
                            `trc_transaction_asset` `a`
                            JOIN `trc_transaction_detail_asset` `b` ON ( ( `b`.`trc_asset_id` = `a`.`id` ) ) 
                        )
                        JOIN `mst_investor` `c` ON ( ( `c`.`id` = `a`.`investor_id` ) ) 
                    ) 
                WHERE
                    (
                        ( `a`.`is_deleted` = 0 ) 
                        AND ( `b`.`is_deleted` = 0 ) 
                        AND ( `c`.`is_deleted` = 0 ) 
                        and b.active = 2
                    ) 
                GROUP BY
                    `a`.`id`
                ");
        }
        
        if($filter == 'N'){
            $model->where('verified',$filter);
        }else{

        }
        
        if($start != 0 && $end != 0){
            $model->whereBetween('date_transaction', [date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
        }else{

        }

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('transaction.investment.action', [
                    'model' => $model,
                    'url_show'=> route('investment.show', base64_encode($model->id)),
                ]);
            })
            
            ->editColumn('total_amount', function($model){
                if($model->currency_code == 'IDR')
                    return "Rp " . number_format($model->total_amount,0,',','.');
                else
                    return "$ " . number_format($model->total_amount,2,',','.');
            })

            ->editColumn('date_transaction', function($model){
                return date('d M Y H:i', strtotime($model->date_transaction)).' WIB';
            })

            ->editColumn('investor_name', function($model){
                if($model->is_dummy == 1){
                    $msg = ucwords($model->investor_name)."  <span class='label label-default'>dummy</span>";
                }else{
                    $msg = ucwords($model->investor_name);
                }

                return $msg;
            })

            ->editColumn('verified', function($model){
                $msg = "";
                if($model->verified == "Y"){
                    $msg = "<span class='label label-success'>Y</span>";
                }else if($model->verified == "N"){
                    $msg = "<span class='label label-danger'>N</span>";
                }else{
                    $msg = "<span class='label label-default'>Unknown</span>";
                }

                return $msg;
            })

            ->editColumn('total_asset', function($model){
                return "<i>".$model->total_asset." asset<i>";
            })

            ->editColumn('status', function($model){
                $status = $model->status;
                $msg = "";
                if($status == "SUCCESS"){
                    $msg = "<span class='label label-success'>Success</span>";
                }else if($status == "PROCESS"){
                    $msg = "<span class='label label-warning'>Process</span>";
                }else if($status == "REJECTED"){
                    $msg = "<span class='label label-danger'>Rejected</span>";
                }else if($status == "VERIFIED"){
                     $msg = "<span class='label label-primary'>Verified</span>";
                } else {
                     $msg = "<span class='label label-default'>Unknown</span>";
                }

                return $msg;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'date_transaction', 'total_amount', 'status', 'investor_name', 'verified', 'total_asset'])
            ->make(true);
    }
}
