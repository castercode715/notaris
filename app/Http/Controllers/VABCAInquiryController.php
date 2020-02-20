<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VABCAInquiry;
use App\Models\MstInvestor;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class VABCAInquiryController extends Controller
{
    public function index()
    {
    	$model = new VABCAInquiry;

        $success = $model->widgetValue('00');
        $failed = $model->widgetValue('01');

    	return view('transaction.va_bca.index', compact('success','failed'));
    }

    public function dataTable()
    {
        $model = VABCAInquiry::query();
        $model->orderBy('created_at','desc');        
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('transaction.va_bca.action', [
                    'model' => $model,
                    'url_show'=> route('vabca-inquiry.detail', $model->id), 
                ]);
            })
            ->editColumn('total_amount', function($model){
                return number_format($model->total_amount,0,',','.').' IDR';
            })
            ->editColumn('payment_flag_status', function($model){
            	$status = null;
            	if($model->payment_flag_status == '00')
            		$status = '<span class="label label-success">SUCCESS</span>';
            	elseif($model->payment_flag_status == '01')
            		$status = '<span class="label label-warning">FAILED</span>';
            	else
            		$status = '<span class="label label-default">-</span>';

                return $status;
            })
            /*
            ->editColumn('date', function($model){
                return date('d M Y H:i', strtotime($model->date)).' WIB';
            })
            ->editColumn('status', function($model){
                return $model->statusLabel();
            })
            ->editColumn('method', function($model){
                return $model->methodLabel();
            })
            ->addIndexColumn()*/
            ->rawColumns(['total_amount','action','payment_flag_status'])
            ->make(true);
    }

    public function reloadWidget()
    {
        $model = new VABCAInquiry;

        $success = $model->widgetValue('00');
        $failed = $model->widgetValue('01');

        return json_encode([
            'success' => $success,
            'failed' => $failed
        ]);
    }

    public function detail($id)
    {
    	$payment = VABCAInquiry::find($id);
    	$inquiry = DB::table('sys_bca_inquiry')->where('request_id', $payment->request_id)->first();
    	$investor = DB::table('mst_investor')->where('phone', $payment->customer_number)->first();

    	return view('transaction.va_bca.detail', compact(['payment','inquiry','investor']));
    }
}
