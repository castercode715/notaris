<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstInvestor;
use App\Models\Monitoringtopup;
use App\Models\TrcVoucherInvestor;
use App\Models\VTranInvestorTopup;
use App\Models\TrcTransactionBalanceInStatus;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class MonitoringtopupController extends Controller
{
    const MODULE_NAME = 'Monitoring Top Up';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

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

        $model = $this->getWidgetValue();
        $verified = $model['verified'];
        $success = $model['success'];
        $pending = $model['pending'];
        $failed = $model['failed'];
        $rejected = $model['rejected'];
        $total = $model['total'];
        $md = $model['md'];
        $pp = $model['pp'];
        $tm = $model['tm'];

        return view('transaction.monitoring-topup.index', compact(
            'verified',
            'Posttgl_awal',
            'Posttgl_ahir',
            'model',
            'success',
            'pending',
            'rejected',
            'failed',
            'md',
            'pp',
            'tm',
            'total'
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

        $model = VTranInvestorTopup::findOrFail($id);

        $status = $model->statusLabel();

        $method = $model->methodLabel();
        
        $history = DB::table('trc_transaction_balance_in_status as a')
                ->where('a.transaction_balance_in_id', $id)
                ->orderBy('created_at','desc')
                ->get();
       
        $uti = new Utility();

        return view('transaction.monitoring-topup.detail', compact([
            'model',
            'status',
            'uti',
            'history',
            'method'
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
        $model = VTranInvestorTopup::query();
              
        if($start == 0){
            $model->orderBy('date','desc');

        }else{
            $model->whereBetween('date', [date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
        }
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('transaction.monitoring-topup.action', [
                    'model' => $model,
                    'url_show'=> route('monitoring-topup.show', $model->id), 
                ]);
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
            ->editColumn('investor', function($model){
                if($model->is_dummy == 1){
                    $result = $model->investor."  <i><span class='label label-default' id='label-tran-status'>dummy</span></i>";
                }else{
                    $result = $model->investor;
                }
                return $result;
            })
            ->editColumn('status', function($model){
                return $model->statusLabel();
            })
            ->editColumn('method', function($model){
                return $model->methodLabel();
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'date', 'amount', 'status','method','investor'])
            ->make(true);
    }

    public function dataHistory($id)
    {
        $model = TrcTransactionBalanceInStatus::query();
        $model->where('transaction_balance_in_id', $id);
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
            ->editColumn('transfer_receipt', function($model){
                if($model->transfer_receipt != null){
                    return "<img src='".'/images/transaction/'.$model->transfer_receipt."' width='300px' class='img-thumbnail' />";
                }
            })
            ->editColumn('status', function($model){
                if($model->transfer_receipt != null){
                    return "<img src='".'/images/transaction/'.$model->transfer_receipt."' width='300px' class='img-thumbnail' />";
                }
            })
            ->editColumn('status', function($model){
                return $model->statusLabel($model->status);
            })
            ->rawColumns(['created_at','transfer_receipt','status'])
            ->make(true);
    }

    public function verify($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $result = true;
        try {
            $verify = DB::select("call tran_topup_verify(?, ?, @vret);", [$id, Auth::user()->id]);
            $vret = DB::select("select @vret as vret")[0]->vret;
        } catch (Exception $e) {
            $result = false;
        }
    }

    public function reject(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'balance_in_id' => 'required',
            'reject_reason' => 'required|string'
        ]);

        if($validate)
        {
            $result = true;
            try {
                $user = Auth::user()->id;
                DB::select("call tran_topup_update_status(?, 'rejected', ?, ?)", [$request->balance_in_id, $user, $request->reject_reason]);
            } catch (Exception $e) {
                $result = false;
            }
        }
    }

    public function recheck(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');
        
        $validate = $this->validate($request, [
            'balance_in_id' => 'required',
            'recheck_reason' => 'required|string'
        ]);

        if($validate)
        {
            $result = true;
            try {
                $user = Auth::user()->id;
                DB::select("call tran_topup_update_status(?, 'pending', ?, ?)", [$request->balance_in_id, $user, $request->recheck_reason]);
            } catch (Exception $e) {
                $result = false;
            }
        }
    }

    public function reloadWidget()
    {
        $data = $this->getWidgetValue();
        $verified = $data['verified'];
        $success = $data['success'];
        $pending = $data['pending'];
        $failed = $data['failed'];
        $rejected = $data['rejected'];
        $total = $data['total'];
        $md = $data['md'];
        $pp = $data['pp'];
        $tm = $data['tm'];

        echo json_encode([
            'success' => $success,
            'pending' => $pending,
            'failed' => $failed,
            'verified' => $verified,
            'rejected' => $rejected,
            'total' => $total,
            'md' => $md,
            'pp' => $pp,
            'tm' => $tm
        ]);
    }

    public function getWidgetValue()
    {
        $model = new VTranInvestorTopup;

        $verified = $model->countBalanceIn('verified');
        $success = $model->countBalanceIn('success');
        $pending = $model->countBalanceIn('pending');
        $failed = $model->countBalanceIn('failed');
        $rejected = $model->countBalanceIn('rejected');
        $total = $model->countBalanceInTotal('');
        $md = $model->countBalanceInByMethod('md');
        $pp = $model->countBalanceInByMethod('pp');
        $tm = $model->countBalanceInByMethod('tm');

        return [
            'verified' => $verified,
            'success' => $success,
            'pending' => $pending,
            'rejected' => $rejected,
            'failed' => $failed,
            'md' => $md,
            'pp' => $pp,
            'tm' => $tm,
            'total' => $total
        ];
    }
}
