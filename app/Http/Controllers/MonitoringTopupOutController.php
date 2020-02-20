<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstInvestor;
use App\Models\MonitoringTopupOut;
use App\Models\VTranInvestorCashout;
use App\Models\TrcTransactionBalanceOutStatus;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;


class MonitoringTopupOutController extends Controller
{
    const MODULE_NAME = 'Monitoring Withdraw';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $model = new VTranInvestorCashout;

        $verified = $model->countBalanceOut('verified');
        $success = $model->countBalanceOut('success');
        $process = $model->countBalanceOut('process');
        $rejected = $model->countBalanceOut('rejected');

        if($request->tgl_awal == ""){
            $Posttgl_awal = 0;
        }else{
            $Posttgl_awal = $request->tgl_awal;
        }

        if($request->tgl_ahir == ""){
            $Posttgl_ahir = 0;
        }else{
            $Posttgl_ahir = $request->tgl_ahir;
        }

        return view('transaction.monitoring-topup-out.index', compact(
            'verified',
            'model',
            'success',
            'process',
            'Posttgl_awal',
            'Posttgl_ahir',
            'rejected'
        ));
    }

    public function filter_date(Request $request)
    {
        

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

        $model = VTranInvestorCashout::findOrFail($id);

        $status = '';
        if($model->status == 'SUCCESS')
            $status = '<span class="label label-success" id="label-tran-status">Success</span>';
        elseif($model->status == 'PROCESS')
            $status = '<span class="label label-warning" id="label-tran-status">Process</span>';
        elseif($model->status == 'VERIFIED')
            $status = '<span class="label label-primary" id="label-tran-status">Verified</span>';
        elseif($model->status == 'REJECTED')
            $status = '<span class="label label-danger" id="label-tran-status">Rejected</span>';

        $history = DB::table('trc_transaction_balance_out_status as a')
                ->where('a.transaction_balance_out_id', $id)
                ->orderBy('created_at','desc')
                ->get();
       
        $uti = new Utility();

        return view('transaction.monitoring-topup-out.detail', compact([
            'model',
            'status',
            'uti',
            'history'
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

    public function dataTable($start = null, $end = null)
    {
        $model = VTranInvestorCashout::query();

        if($start == 0){
            $model->orderBy('date','desc');

        }else{
            $model->whereBetween('date', [date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
        }


        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('transaction.monitoring-topup-out.action', [
                    'model' => $model,
                    'url_show'=> route('monitoring-cashout.show', $model->id),
                ]);
            })
            ->addColumn('age', function($model){
                $label = '';
                if($model->status == 'VERIFIED' || $model->status == 'REJECTED')
                    $label = "<span class='label label-default'>-</span>";
                else
                {
                    $day = date_diff(date_create(date_format(date_create($model->date), 'Y-m-d')), date_create(date('Y-m-d')));
                    if($day->format("%a") == 0)
                        $label = "<span class='label label-success'>0</span>";
                    elseif($day->format("%a") == 1)
                        $label = "<span class='label label-warning'>1</span>";
                    elseif($day->format("%a") > 1)
                        $label = "<span class='label label-danger'>".$day->format("%a")."</span>";
                }
                return $label;
            })
            ->editColumn('amount', function($model){
                if($model->currency_code == 'IDR')
                    return "Rp " . number_format($model->amount,0,',','.');
                else
                    return "$ " . number_format($model->amount,2,',','.');
            })
            ->editColumn('date', function($model){
                return date('d M Y H:i', strtotime($model->date)).' WIB';
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
            ->rawColumns(['action', 'date', 'amount', 'status', 'age'])
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

        $result = true;
        try {
            $user = Auth::user()->id;
            DB::select("call tran_cashout_process(?, ?, @vret)", [$id, $user]);
            $vret = DB::select("select @vret as vret")[0]->vret;
            $status = explode('|', $vret);
            if($status[0] != '0000')
                $result = false;
            else {
                $trc_balance_out = DB::table('trc_transaction_balance_out')->find($id);
                $investor = DB::table('mst_investor')->find($trc_balance_out->investor_id);
                $device = '["'.$investor->device_id.'"]';
                $message = '{
                  "title":"Penarikan Dana",
                  "body":"Permintaan penarikan dana Anda telah disetujui dan akan diproses 1x24 jam."
                }';
                $uti = new Utility;
                $kirim_app_notif = $uti->sendAppNotif($device, $message);
            }
        } catch (Exception $e) {
            $result = false;
        }
        echo json_encode($result);
    }

    public function reject(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'balance_out_id' => 'required',
            'reject_reason' => 'required|string'
        ]);

        $result = false;
        if($validate)
        {
            try {
                $user = Auth::user()->id;
                DB::select("call tran_cashout_rejected(?, ?, ?, @vret)", [$request->balance_out_id, $user, $request->reject_reason]);
                $vret = DB::select("select @vret as vret")[0]->vret;
                $status = explode('|', $vret);
                if($status[0] == '0000')
                    $result = true;
            } catch (Exception $e) {
                $result = false;
            }
            /*$result = true;
            try {
                $user = Auth::user()->id;
                DB::select("call tran_cashout_update_status(?, 'rejected', ?, ?)", [$request->balance_out_id, $user, $request->reject_reason]);
            } catch (Exception $e) {
                $result = false;
            }*/
        }
        echo json_encode($result);
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
            else {
                $trc_balance_out = DB::table('trc_transaction_balance_out')->find($id);
                $investor = DB::table('mst_investor')->find($trc_balance_out->investor_id);
                $device = '["'.$investor->device_id.'"]';
                $message = '{
                  "title":"Penarikan Dana",
                  "body":"Dana Anda telah berhasil ditransfer ke rekening."
                }';
                $uti = new Utility;
                $kirim_app_notif = $uti->sendAppNotif($device, $message);
            }
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
