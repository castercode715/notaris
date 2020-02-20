<?php

namespace App\Http\Controllers;

use App\Models\TrcOrderEcommerce;
use App\Models\MstProductEcommerce;
use App\Models\TrcOrderEcommerceLogModels;
use App\Models\MstInvestor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class TrcOrderEcommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new TrcOrderEcommerce();
        $order_id = $model->id;

        return view('transaction.order.index', compact('model','order_id'));
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

    public function print($id)
    {
        $id = base64_decode($id);
        $model = TrcOrderEcommerce::findOrFail($id);
        $inv = MstInvestor::findOrFail($model->investor_id);
        $uti = new utility();

        $detailProduct = DB::table('trc_order_details_ecommerce as a')
                            ->join('trc_order_ecommerce as b', 'b.id','=','a.order_id')
                            ->join('mst_product_ecommerce as c','c.id','=','a.product_id')
                            ->where('a.order_id', $model->id)
                            ->select(
                                'a.product_quantity as qty',
                                'a.discount as discount',
                                'a.product_id as product_id',
                                'a.total_price as price',
                                'a.discount',
                                'a.price as harga',
                                'a.id'
                            )
                            ->get();

        $status = DB::table('trc_order_ecommerce_log as a')
                    ->where('a.order_id', $id)
                    ->orderBy('a.id', 'DESC')
                    ->first();

        return view('transaction.order.detail-print', compact([
            'model',
            'uti',
            'inv',
            'detailProduct',
            'status',
        ]));

    }

    public function show($id)
    {
        $id = base64_decode($id);
        $model = TrcOrderEcommerce::findOrFail($id);
        $inv = MstInvestor::findOrFail($model->investor_id);
        $uti = new utility();

        $detailProduct = DB::table('trc_order_details_ecommerce as a')
                            ->join('trc_order_ecommerce as b', 'b.id','=','a.order_id')
                            ->join('mst_product_ecommerce as c','c.id','=','a.product_id')
                            ->where('a.order_id', $model->id)
                            ->select(
                                'a.product_quantity as qty',
                                'a.discount as discount',
                                'a.product_id as product_id',
                                'a.total_price as price',
                                'a.discount',
                                'a.price as harga',
                                'a.id'
                            )
                            ->get();

        

        $status = DB::table('trc_order_ecommerce_log as a')
                    ->where('a.order_id', $id)
                    ->orderBy('a.id', 'DESC')
                    ->first();

        return view('transaction.order.detail', compact([
            'model',
            'uti',
            'inv',
            'detailProduct',
            'status',
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

    public function ajaxStatus($id, $status)
    { 
        if ($status == "") {
            $result = DB::table('trc_order_ecommerce_log as a')
                ->where('a.order_id', $id)
                ->orderBy('a.id', 'DESC')
                ->select('a.status')
                ->first();
        }else{
            $result = DB::table('trc_order_ecommerce_log as a')
                ->where('a.order_id', $id)
                ->where('a.status', $status)
                ->orderBy('a.id', 'DESC')
                ->select('a.status')
                ->first();
        }

        return $result->status;
        
    }

    public function status($status = "")
    {
        return $status;
    }

    public function dataTable()
    {
        $model = DB::table('trc_order_ecommerce as a')
                ->select('a.*', 'b.status')
                ->join('trc_order_ecommerce_log as b', 'b.order_id', '=', 'a.id')
                ->where('b.flag', 1)
                ->orderBy('a.id', 'DESC')
                ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('transaction.order.action', [
                    'model' => $model,
                    'url_show'=> route('order.show', base64_encode($model->id)),
                    'url_edit'=> route('order.edit', base64_encode($model->id)),
                    'url_destroy'=> route('order.destroy', base64_encode($model->id))
                ]);
            })

            ->editColumn('date_transaction', function($model){
                return date('d-m-Y H:i', strtotime($model->date_transaction))." WIB";
            })

            ->editColumn('status', function($model){

                $msg = "";
                if($model->status == "NEW"){
                    $msg = "<span class='label label-success'>NEW</span>";
                }else if($model->status == "PROCESS"){
                    $msg = "<span class='label label-warning'>PROCESS</span>";
                }else if($model->status == "DELIVERY"){
                    $msg = "<span class='label label-info'>DELIVERY</span>";
                }else if($model->status == "RECEIVED"){
                    $msg = "<span class='label label-danger'>RECEIVED</span>";
                }else{
                    $msg = "<span class='label label-danger'>UNKNOWN</span>";
                }

                return $msg;
            })

            ->editColumn('investor_id', function($model){
                $uti = new utility();
                return $uti->investor($model->investor_id);
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'date_transaction', 'investor_id','status'])
            ->make(true);
    }

    public function detailProduct($id)
    {
        $model = DB::table('trc_order_details_ecommerce as a')
                    ->where('a.order_id', $id)
                    ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.apt-asset.unit.action', [
                    'model' => $model,
                    'url_show'=> route('unit-asset.show', base64_encode($model->code_unit)),
                    'url_edit'=> route('unit-asset.edit', base64_encode($model->code_unit)),
                    'url_destroy'=> route('unit-asset.destroy', base64_encode($model->code_unit))
                ]);
            })

            

            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by','status','type_apt'])
            ->make(true);
    }

    public function processOrder($id){
        $id = base64_decode($id);

        dd($id);
    }

    public function getDetail($id)
    {
        $id = base64_decode($id);
        $result = DB::table('trc_order_detail_atribute_ecommerce as a')
                ->join('trc_order_details_ecommerce as b','b.id','=','a.order_detail_id')
                ->where('a.order_detail_id', $id)
                ->select(
                    'b.product_id', 
                    'a.product_atrribute_id',
                    'a.tenor_product_id'
                )
                ->first();

        $model = MstProductEcommerce::findOrFail($result->product_id);

        $attr = DB::table('mst_product_attribute as a')
                    ->join('mst_attribute_ecommerce as b','b.id','=','a.attribute_ecommerce_id')
                    ->select('b.name', 'a.value')
                    ->where('a.id', $result->product_atrribute_id)
                    ->get();


        return view('transaction.order.detail-attribute', compact([
            'result',
            'model',
            'attr',
        ]));
    }

    public function getStatus($id)
    {
        $result = DB::table('trc_order_ecommerce_log as a')
                    ->where('a.order_id', $id)
                    ->orderBy('a.id', 'DESC')
                    ->first();

        return response()->json([
            'data' => $result->status
        ]);
    }

    public function invoicePane()
    {
        return view('transaction.order.tab.detail');
    }

    public function historyPane()
    {
        return view('transaction.order.tab.history');
    }

    public function dataTableHistory($id)
    {
        $model = TrcOrderEcommerceLogModels::query();
        $model->where('order_id', $id);

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('transaction.order.action', [
                    'model' => $model,
                    'url_show'=> route('order.show', base64_encode($model->id)),
                    'url_edit'=> route('order.edit', base64_encode($model->id)),
                    'url_destroy'=> route('order.destroy', base64_encode($model->id))
                ]);
            })

            ->editColumn('date', function($model){
                return date('d-m-Y H:i', strtotime($model->date))." WIB";
            })

            ->editColumn('by', function($model){

                $result = new Utility();
                return $result->getUser($model->by);

            })

            ->editColumn('status', function($model){

                $msg = "";
                if($model->status == "NEW"){
                    $msg = "<span class='label label-success'>NEW</span>";
                }else if($model->status == "PROCESS"){
                    $msg = "<span class='label label-warning'>PROCESS</span>";
                }else if($model->status == "DELIVERY"){
                    $msg = "<span class='label label-info'>DELIVERY</span>";
                }else if($model->status == "RECEIVED"){
                    $msg = "<span class='label label-danger'>RECEIVED</span>";
                }else{
                    $msg = "<span class='label label-danger'>UNKNOWN</span>";
                }

                return $msg;

            })

            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'date','id','status'])
            ->make(true);

    }

    public function logOrder($id, Request $request)
    {
        $data_id = $id;
        $model = new TrcOrderEcommerceLogModels();

        return view('transaction.order.log.form',compact('model','data_id'));

    }

    public function logSave(Request $request)
    {
        $validate = $this->validate($request,[
            'note'          => 'string',
            'order_id' => 'required',
            'status' => 'required',
            'no_resi' => 'required|string',
            'investor_id' => 'required|string',
            'ex_name' => 'required|string'
        ]);

        $userId = Auth::user()->id;
        $no_resi = $request->no_resi;
        $ex_name = $request->ex_name;
        $investor_id = $request->investor_id;
        $investor = DB::table('mst_investor')->find($investor_id);
        $order = DB::table('trc_order_ecommerce')->find($request->order_id);

        /* update is lewat */
        $jilmek = TrcOrderEcommerceLogModels::where('order_id', $request->order_id)
                                            ->update(['flag' => 0]);

        /* insert new status */
        $data = [
            'order_id'  => $request->order_id,
            'note'      => $request->note,
            'status'    => strtoupper($request->status),
            'date'      => date('Y-m-d H:i:s'),
            'by'        => $userId,
            'flag'      => 1,
        ];

        $model = TrcOrderEcommerceLogModels::create($data);

        /* =======================
            APP Notifications 
        ======================= */
        if($request->status == "process"){
            
            /* Push Email */
            $emailSubject = 'Konfirmasi Pemesanan Produk';
            $emailName = $investor->full_name;
            $emailEmail = $investor->email;
            $emailMessage = '<p>';
            $emailMessage .= 'Hai <b>'.$investor->full_name.'</b>, <br>';
            $emailMessage .= 'Pesanan anda dengan No Order #'.$order->no_order.' sedang di proses lho! Kami akan melakukan pengemasan dan pengiriman produk paling lambat 1 - 2 hari. Silahkan tunggu konfirmasi selanjutnya yaa! </p>';
            $emailMessageText = 'Pesanan anda dengan No Order #'.$order->no_order.' sedang di proses lho! Kami akan melakukan pengemasan dan pengiriman produk paling lambat 1 - 2 hari. Silahkan tunggu konfirmasi selanjutnya yaa!';

            /* Push App */
            $device = '["'.$investor->device_id.'"]';
            $message = '{
              "title":"Konfirmasi Pemesanan Produk",
              "body":"Hai '.$investor->full_name.', Pesanan anda dengan No Order #'.$order->no_order.' sedang di proses. Silahkan menunggu konfirmasi selanjutnya yaa!"
            }';

            $uti = new Utility;
            $kirim_app_notif = $uti->sendAppNotif($device, $message);
            $kirim_email = $uti->sendMail($emailEmail, $emailName, $emailSubject, $emailMessage, $emailMessageText);

        }elseif($request->status == "delivery"){

            /* Push Email */
            $emailSubject = 'Konfirmasi Pemesanan Produk';
            $emailName = $investor->full_name;
            $emailEmail = $investor->email;
            $emailMessage = '<p>';
            $emailMessage .= 'Hai <b>'.$investor->full_name.'</b>, <br>';
            $emailMessage .= 'Pesanan anda dengan No Order #'.$order->no_order.' sudah dikemas dan dikirim lho! Silahkan tunggu yaa! </p>';
            $emailMessageText = 'Pesanan anda dengan No Order #'.$order->no_order.' sudah dikemas dan dikirim lho! Silahkan tunggu yaa!';

            /* Push App */
            $device = '["'.$investor->device_id.'"]';
            $message = '{
              "title":"Konfirmasi Pemesanan Produk",
              "body":"Hai '.$investor->full_name.', Pesanan anda dengan No Order #'.$order->no_order.' sudah dikirim lho! Silahkan tunggu yaa!"
            }';

            $uti = new Utility;
            $kirim_app_notif = $uti->sendAppNotif($device, $message);
            $kirim_email = $uti->sendMail($emailEmail, $emailName, $emailSubject, $emailMessage, $emailMessageText);

        }else{

        }

        
        

        if($no_resi != "Empty" and $ex_name != "Empty"){

            /* =======================
                UPDATE Order 
            ======================= */

            $model_order = TrcOrderEcommerce::findOrFail($request->order_id);

            $res = [
                'expedition_name' => $ex_name,
                'no_resi' => $no_resi
            ];


            $model_order->update($res);

        }

    }
}
