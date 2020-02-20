<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstInvestor;
use App\Models\LoanRequest;
use App\Models\VTranInvestorCashout;
use App\Models\TrcTransactionBalanceOutStatus;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;


class LoanRequestController extends Controller
{
    const MODULE_NAME = 'Loan Request';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        // $model = new VTranInvestorCashout;

        // $verified = $model->countBalanceOut('verified');
        // $success = $model->countBalanceOut('success');
        // $process = $model->countBalanceOut('process');
        // $rejected = $model->countBalanceOut('rejected');

        return view('loan-request.index');
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
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $model = LoanRequest::findOrFail($id);

        return view('loan-request.detail', compact([
            'model'
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

    public function dataTable()
    {
        $model = LoanRequest::orderBy('id', 'desc')->get();
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('loan-request.action', [
                    'model' => $model,
                    'url_show'=> route('loan-request.show', $model->id),
                ]);
            })
            ->addIndexColumn()
            // ->rawColumns(['action'])
            ->make(true);
    }

    public function dataHistory($id)
    {
        $model = TrcTransactionBalanceOutStatus::query();
        $model->where('transaction_balance_out_id', $id);
        $model->orderBy('created_at','desc');

        return DataTables::of($model)
            ->editColumn('created_at', function($model){
                return date('d M Y H:i', strtotime($model->created_at)).' WIB';
            })
            ->editColumn('created_by', function($model){
                if($model->created_by != null){
                    $uti = new Utility;
                    return $uti->getUser($model->created_by);
                }
            })
            ->rawColumns(['created_at'])
            ->make(true);
    }

    public function process($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        DB::update("update trc_loan_request set status = 'PROCESS' where id = ?", [$id]);

        echo 1;
    }

    public function decline($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $user = Auth::user()->id;
        DB::update("update trc_loan_request set status = 'DECLINE' where id = ?", [$id]);

        echo 1;
    }

    public function verify($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');
        
        $result = true;
        try {
            $user = Auth::user()->id;
            DB::select("call tran_cashout_verified(?, ?, @vret)", [$id, $user]);
            $vret = DB::select("select @vret as vret")[0]->vret;
            $status = explode('|', $vret);
            if($status[0] != '0000')
                $result = false;
        } catch (Exception $e) {
            $result = false;
        }
        echo json_encode($result);
    }

    public function verify2($id)
    {
        $result = true;
        $message = 'Success';

        //Insert To trc_balance
        $balance = DB::table('trc_transaction_balance_out as a')
                    ->where('a.id',$id)
                    ->select('a.*')
                    ->first();

        $inv_id = $balance->investor_id;
        $vtransid = $balance->id;
        $notrans = $balance->transaction_number;
        $dateN = $balance->date;
        $vamount = $balance->amount;
        $info = "Withdraw Balance";
        $infest = "n";
        $type = "out";

        // dd($inv_id, $vtransid, $notrans, $dateN, $vamount);



        DB::beginTransaction();

        try {
            
            $model = DB::select("call p_balance_update(?, ?, ?, ?, ?, ?, ?, ?, @vret);", [$inv_id, $vtransid, $notrans, $dateN, $vamount, $info, $infest, $type]);
            $vret = DB::select("select @vret as vret")[0]->vret;
            // dd($vret);

            if(substr($vret, 0, 4) == '0000')
            {
                //Update status balance in
                DB::update("update trc_transaction_balance_out set 
                    status='VERIFIED'
                    where id = ".$id."");

                //Insert Status Log
                DB::table('trc_transaction_balance_out_status')->insert(array('transaction_balance_out_id' => $vtransid, 'response' => '', 'status' => 'VERIFIED', 'information' => '', 'created_at' => date('Y-m-d H:i:s')));
            }

            DB::commit();
        } catch(Exception $e) {
            $message = $e->getMessage();
            $result = false;
            DB::rollBack();
        }


            //Get Balance to update class
            // $last_balance = DB::select("select f_investor_balance(?) as balance", [1])[0]->balance;

            // if($last_balance >= 100000 && $last_balance <= 300000000){
            //     //update to Silver
            // }elseif($last_balance > 300000000 && $last_balance <= 500000000){
            //     //update to gold
            // }elseif($last_balance > 500000000 && $last_balance <= 2000000000){
            //     //update to platinum
            // }else{
            //     //update to premium 
            // }
        // }
        if ($result)
            return redirect('transaction/monitoring-topup-out')->with('success','Data successfuly verified');
        else
            return redirect('transaction/monitoring-topup-out')->with('error',$message);
    }

    public function reloadWidget()
    {
        $model = new VTranInvestorCashout;
        $verified = $model->countBalanceOut('verified');
        $success = $model->countBalanceOut('success');
        $process = $model->countBalanceOut('process');
        $rejected = $model->countBalanceOut('rejected');

        echo json_encode([
            'success' => $success,
            'process' => $process,
            'rejected' => $rejected,
            'verified' => $verified
        ]);
    }
}
