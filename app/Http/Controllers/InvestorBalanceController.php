<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrcBalance;
use DataTables;
use DB;
use App\Utility;

class InvestorBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

    public function getBalance($id)
    {
        $model = DB::select("select f_investor_balance(?) as balance", [$id])[0]->balance;

        echo $model;
    }

    public function getInvest($id)
    {
        $model = DB::select('select f_investor_total_asset('.$id.') as investment');

        foreach ($model as $key) {
            # code...
            $investment_inv = $key->investment;
        }

        echo $investment_inv;
    }

    public function dataTable($id)
    {
        $model = DB::table('trc_balance as a')
                ->where('a.investor_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->orderBy('a.id','desc')
                ->select(
                    'a.id',
                    'a.date',
                    'a.credit',
                    'a.debit',
                    'a.balance',
                    'a.information'
                );
        // $model = DB::select("
        //     SELECT 
        //         id, 
        //         date, 
        //         credit, 
        //         debit, 
        //         balance, 
        //         information
        //     FROM
        //         trc_balance
        //     WHERE
        //         investor_id = ".$id." 
        //     AND active = 1
        //     AND is_deleted = 0
        //     ORDER BY date DESC;
        // ");
        // dd($model);
        return DataTables::of($model)
                ->editColumn('date', function($model){
                    return date('d-m-Y H:i', strtotime($model->date));
                })
                ->editColumn('credit', function($model){
                    $credit = null;
                    if($model->credit != null || $model->credit != '')
                        $credit = "Rp " . number_format($model->credit,0,',','.');
                    return $credit;
                })
                ->editColumn('debit', function($model){
                    $debit = null;
                    if($model->debit != null || $model->debit != '')
                        $debit = "Rp " . number_format($model->debit,0,',','.');
                    return $debit;
                })
                ->editColumn('balance', function($model){
                    $balance = null;
                    if($model->balance != null || $model->balance != '')
                        $balance = "Rp " . number_format($model->balance,0,',','.');
                    return $balance;
                })
                // ->editColumn('balance', function($model){
                //     return $model->balance ? "Rp " . number_format($model->balance,0,',','.') : null;
                // })
                ->addIndexColumn()
                ->rawColumns(['id','date','credit','debit','balance'])
                ->make(true);
    }

    public function pane()
    {
        return view('transaction.investor.tab.balance');
    }
}
