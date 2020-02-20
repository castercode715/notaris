<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstInvestor;
use App\Models\MstBank;
use App\Models\TrcInvBank;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DataTables;
use DB;
use App\Utility;

class InvestorInternetBankingController extends Controller
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
        $model = new TrcInvBank();

        $banklist = MstBank::where('active','1')
                    ->where('is_deleted','0')
                    ->where('card_type',"C")
                    ->pluck('name','id')
                    ->all();

        $payment_methode = [
            '1' => 'Credit Card',
            '3' => 'Virtual Account',
        ];

        return view('transaction.investor.tab.form.form-internet_banking', compact([
            'id',
            'model',
            'banklist',
            'payment_methode'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'bank_id'   => 'required',
            'account_holder_name'  => 'required',
            'account_number'  => 'required',
            'payment_methode'  => 'required',
            'validity_period'  => 'required_if:payment_methode,1',
            'ccv'  => 'required_if:payment_methode,1'
        ];

        $messages = [
            'validity_period.required_if'   => 'The validity period field is required when payment methode is Credit Card',
            'ccv.required_if'   => 'The ccv field is required when payment methode is Credit Card'
        ];

        if($this->validate($request, $rules, $messages))
        {
            $data = [
                'investor_id' => $request->investor_id,
                'bank_id' => $request->bank_id,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'payment_methode' => $request->payment_methode,
                'active' => "1"
            ];

            if($request->payment_methode == 1)
            {
                $data = array_merge($data, [
                    'validity_period' => $request->validity_period,
                    'ccv' => $request->ccv
                ]);
            }

            $model = TrcInvBank::create($data);
            return $model;
        }
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
        $model = TrcInvBank::findOrFail($id);

        $banklist = MstBank::where('active','1')
                    ->where('is_deleted','0')
                    ->where('card_type',"C")
                    ->pluck('name','id')
                    ->all();

        $payment_methode = [
            '1' => 'Credit Card',
            '3' => 'Virtual Account',
        ];
        
        return view('transaction.investor.tab.form.form-internet_banking', compact([
            'id',
            'model',
            'banklist',
            'payment_methode'
        ]));
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
        $rules = [
            'bank_id'   => 'required',
            'account_holder_name'  => 'required',
            'account_number'  => 'required',
            'payment_methode'  => 'required',
            'validity_period'  => 'required_if:payment_methode,1',
            'ccv'  => 'required_if:payment_methode,1'
        ];

        $messages = [
            'validity_period.required_if'   => 'The validity period field is required when payment methode is Credit Card',
            'ccv.required_if'   => 'The ccv field is required when payment methode is Credit Card'
        ];

        if($this->validate($request, $rules, $messages))
        {
            $active = $request->get('active') ? 1 : 0;

            $data = [
                'bank_id' => $request->bank_id,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'payment_methode' => $request->payment_methode,
                'active' => $active
            ];

            if($request->payment_methode == 1)
            {
                $data = array_merge($data, [
                    'validity_period' => $request->validity_period,
                    'ccv' => $request->ccv
                ]);
            }

            $model = TrcInvBank::findOrFail($id);
            $model->update($data);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_at   = date('Y-m-d H:i:s');

        DB::update("update trc_inv_bank set 
            deleted_at='".$deleted_at."',
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable($id)
    {
        $model = DB::table('trc_inv_bank as a')
                ->join('mst_bank as b','a.bank_id','=','b.id')
                ->where('a.investor_id',$id)
                ->where('a.is_deleted','0')
                ->where('b.card_type',"C")
                ->select('a.id','b.name as bank','b.image_logo','a.account_holder_name','a.account_number')
                ->orderBy('a.id','desc')
                ->get();

        return DataTables::of($model)
                ->addColumn('action', function($model){
                    return view('transaction.investor.tab.action', [
                        'model' => $model,
                        'url_edit' => route('internet-banking.edit', $model->id),
                        'url_destroy' => route('internet-banking.destroy', $model->id)
                    ]);
                })
                ->editColumn('image_logo', function($model){
                    return "<img src='".'/images/bank/'.$model->image_logo."' width='100px' height='100px' />";
                })
                // ->editColumn('payment_methode', function($model){
                //     $uti = new Utility; 
                //     return $uti->getPaymentMethod($model->payment_methode);
                // })
                ->addIndexColumn()
                ->rawColumns(['action', 'image_logo'])
                ->make(true);
    }
}
