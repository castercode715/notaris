<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MstInvestor;
use App\Models\MstLanguage;
use App\Models\MstRedeemTermCondition;
use App\Models\MstRedeemVoucher;
use App\Models\MstRedeemInvestor;
use App\Models\MstRedeemLang;
use App\Models\MstTCRedeem;
use App\Utility;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MstRedeemVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.cashback.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstRedeemVoucher;
        $term = MstRedeemTermCondition::orderBy('created_at', 'desc')->get();
        return view('master.cashback.create', compact('model', 'term'));
    }

    public function createNew($id, $lang)
    {
        $language = MstLanguage::findOrFail($lang);
        return view('master.cashback.create-new', compact('id','language'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $tc = [];
        // if ($request->tc_code[0] != '') {
        //     foreach ($request->tc_code as $value) {
        //         $model = MstRedeemTermCondition::findOrFail($value);
        //         $tc[] = $model;
        //     }
        // }
        // $request->session()->flash('tc', $tc);
        $request->validate([
            'title' => 'required',
            'amount' => 'required|min:1',
            'quota' => 'required',
            'type' => [
                'required',
                Rule::in('PUBLIC', 'PRIVATE')
            ],
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'investor' => 'required_if:type,PRIVATE',
            'image' => 'required|image',
            'desc' => 'required',
            // 'tc_code' => 'required|array|min:1',
            'tc_code' => [
                'required',
            ]
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();
            // create header
            $model = MstRedeemVoucher::create([
                'code_redeem' => $request->code_redeem,
                'amount' => $request->amount,
                'quota' => $request->quota,
                'remain_quota' => $request->quota,
                'status' => 'DRAFT',
                'type' => $request->type,
                'date_start' => date('Y-m-d', strtotime($request->date_start)),
                'date_end' => date('Y-m-d', strtotime($request->date_end)),
                'created_by' => $userId,
                'is_deleted' => 0
            ]);
            // insert language
            $lang = [
                'redeem_voucher_id' => $model->id,
                'code' => 'IND',
                'title' => $request->title,
                'description' => $request->desc
            ];

            if ($img = $request->image) {
                $img_name = time() . '.' . $img->extension();
                $img->storeAs('voucher', $img_name, 'public-img');
                $lang = array_merge($lang, ['image' => $img_name]);
            }

            MstRedeemLang::create($lang);

            if ($file = $request->file('investor')) {
                $file_name = time() . '.' . $file->extension();
                $file_investor = $file->storeAs('voucher', $file_name, 'public-file');
                $path = base_path() .DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR. $file_investor;
                if(!file_exists($path) || !is_readable($path)) {
                    DB::rollBack();
                    throw new Exception("File not found", 500);
                }

                $investors = [];
                $no = 1;
                if (($handle = fopen($path, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                        $investors[] = $row[0];
                        $no++;
                    }
                }

                $investors = array_slice($investors, 1);
                foreach ($investors as $value) {
                    $investor = [
                        'investor_id' => $value,
                        'redeem_voucher_id' => $model->id,
                        'used' => 'N'
                    ];
                    MstRedeemInvestor::create($investor);
                }
            }

            if (!empty($request->tc_code && $request->tc_code[0] != null)) {
                foreach ($request->tc_code as $value) {
                    MstTCRedeem::create([
                        'redeem_voucher_id' => $model->id,
                        'term_code' => $value
                    ]);
                }
            } else {
                DB::rollBack();
                return redirect()->route('cashback.create')->with('error', 'Term Conds cannot be blank')->withInput();
            }

            // notifikasi
            Utility::addNotificationN(
                strtolower($model->type), 
                null, 
                'VOUCHER', 
                'Yeay! Ada Cashback untuk Anda. Cek voucher Kop-Aja Anda sekarang', 
                'Selamat, Anda mendapatkan cashback sebesar Rp '.number_format($model->amount, 0, ',', '.').'. Silahkan buka aplikasi Kop-Aja Anda lalu masuk ke menu voucher untuk mendapatkan cashback', 
                'Voucher/detail_redeem/'.base64_encode($model->id)
            );
            // email
            Utility::voucherCashbackEmailNotification(strtolower($model->type), $model->id);
            // app notifikasi
            $investors = [];
            if ($model->type == 'PUBLIC') {
                $investors = MstInvestor::select('device_id')
                    ->where([
                        'is_deleted' => 0,
                        'active' => 1,
                        'is_completed' => 1
                    ])
                    ->whereNotNull('device_id')
                    ->get();
            } elseif ($model->type == 'PRIVATE') {
                $investors = MstRedeemInvestor::select(['mst_investor.device_id'])
                    ->join('mst_investor', 'mst_redeem_investor.investor_id','=','mst_investor.id')
                    ->where([
                        'mst_investor.active' => 1,
                        'mst_investor.is_completed' => 1,
                        'mst_investor.is_deleted' => 0,
                        'redeem_voucher_id' => $model->id
                    ])
                    ->whereRaw("device_id <> '' ")
                    ->whereNotNull('device_id')
                    ->get();
            }
            
            Utility::cashbackAppNotification($model->id, $investors);

            DB::commit();
            return redirect()->route('cashback.show', $model->id)->with('success', 'Created');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->route('cashback.create')->with('error', $e->getErrors());
        }
    }

    public function storeNew(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image',
            'desc' => 'required'
        ]);
        
        $img = $request->image;
        $img_name = time() . '.' . $img->extension();
        $img->storeAs('voucher', $img_name, 'public-img');

        $model = MstRedeemLang::create([
            'redeem_voucher_id' => $request->id,
            'code' => $request->code,
            'title' => $request->title,
            'description' => $request->desc,
            'image' => $img_name
        ]);
        if($model)
            return redirect()->route('cashback.show', $request->id)->with('success', 'Success add language');
        else 
            return redirect()->back()->with('error', 'Failed to save')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = MstRedeemVoucher::findOrFail($id);
        $ind = MstRedeemLang::where('code','IND')
                ->where('redeem_voucher_id', $id)
                ->first();
        $other = MstLanguage::whereNotIn('code',['IND'])->get();
        
        return view('master.cashback.view', compact('model','other','ind'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MstRedeemVoucher::findOrFail($id);
        $term = MstRedeemTermCondition::orderBy('created_at', 'desc')->get();
        $myterm = MstTCRedeem::where('redeem_voucher_id', $id)->get();
        return view('master.cashback.edit', compact('model','term','myterm'));
    }

    public function editNew($id)
    {
        $model = MstRedeemLang::findOrFail($id);
        $language = MstLanguage::findOrFail($model->code);
        return view('master.cashback.edit-new', compact('model','language'));
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
        $request->validate([
            'amount' => 'required|min:1',
            'quota' => 'required',
            'type' => [
                'required',
                Rule::in('PUBLIC', 'PRIVATE')
            ],
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);
        
        DB::beginTransaction();
        try {
            $data = [
                'amount' => $request->amount,
                'quota' => $request->quota,
                'type' => $request->type,
                'date_start' => date('Y-m-d', strtotime($request->date_start)),
                'date_end' => date('Y-m-d', strtotime($request->date_end)),
                'updated_by' => Auth::id()
            ];
            $model = MstRedeemVoucher::findOrFail($id);
            if($model->update($data)) {
                // upload investor
                if ($file = $request->file('investor')) {
                    $file_name = time() . '.' . $file->extension();
                    $file_investor = $file->storeAs('voucher', $file_name, 'public-file');
                    $path = base_path() .DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR. $file_investor;
                    if(!file_exists($path) || !is_readable($path)) {
                        DB::rollBack();
                        throw new Exception("File not found", 500);
                    }
    
                    $investors = [];
                    $no = 1;
                    if (($handle = fopen($path, 'r')) !== false) {
                        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                            $investors[] = $row[0];
                            $no++;
                        }
                    }
                    $investors = array_slice($investors, 1);
                    // delete old data
                    MstRedeemInvestor::where('redeem_voucher_id', $id)->delete();

                    foreach ($investors as $value) {
                        $investor = [
                            'investor_id' => $value,
                            'redeem_voucher_id' => $model->id,
                            'used' => 'N'
                        ];
                        MstRedeemInvestor::create($investor);
                    }
                }
                // update tc
                if (!empty($request->tc_code && $request->tc_code[0] != null)) {
                    MstTCRedeem::where('redeem_voucher_id', $id)->delete();
                    foreach ($request->tc_code as $value) {
                        MstTCRedeem::create([
                            'redeem_voucher_id' => $model->id,
                            'term_code' => $value
                        ]);
                    }
                } else {
                    DB::rollBack();
                    return redirect()->route('cashback.edit', $model->id)->with('error', 'Term Conds cannot be blank')->withInput();
                }
    
                DB::commit();
                return redirect()->route('cashback.show', $model->id)->with('success', 'Created');
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->route('cashback.edit', $model->id)->with('error', $e->getErrors());
        }
    }

    public function updateNew(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description
            ];

            if ($img = $request->file('image')) {
                $img_name = time() . '.' . $img->extension();
                $img->storeAs('voucher', $img_name, 'public-img');
                $data = array_merge($data, ['image'=>$img_name]);
            }
            $model = MstRedeemLang::findOrFail($id);
            
            if ($model->update($data)) {
                DB::commit();
                return redirect()->route('cashback.show', $model->redeem_voucher_id)->with('success', 'Description updated');
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->route('cashback.edit-new', $model->id)->with('error', $e->getErrors());
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
        // 
    }

    public function data()
    {
        $model = DB::table('mst_redeem_voucher as v')
            ->join('mst_redeem_lang as vl', 'v.id', 'vl.redeem_voucher_id')
            ->select('v.id', 'vl.title', 'v.amount', 'v.remain_quota', 'v.type', 'v.date_start', 'v.date_end', 'v.status')
            ->where('vl.code', 'IND')
            ->where('v.is_deleted', 0)
            ->orderBy('v.id', 'desc')
            ->get();

        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return view('master.cashback.action', [
                    'model' => $model,
                    'ushow' => route('cashback.show', $model->id),
                    'uedit' => route('cashback.edit', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function investors($id)
    {
        $model = DB::table('mst_redeem_investor')
                ->select(['mst_investor.id','mst_investor.full_name','mst_redeem_investor.used'])
                ->join('mst_investor', 'mst_redeem_investor.investor_id', 'mst_investor.id')
                ->where('redeem_voucher_id', $id)
                ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return '<a href="" class="btn btn-xs btn-danger btn-delete-investor"><i class="fa fa-close"></i></a>';
            })
            ->editColumn('used', function($model){
                return $model->used == 'N' ? '<span class="label label-success">NO</span>' : '<span class="label label-warning">USED</span>';
            })
            ->rawColumns(['action','used'])
            ->make(true);
    }

    public function publish($id)
    {
        DB::beginTransaction();
        try {
            $model = MstRedeemVoucher::find($id);
            $model->status = 'PUBLISHED';
            $model->updated_by = Auth::id();
            $model->update();
            DB::commit();
            return redirect()->route('cashback.show', $id)->with('success', 'Published');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('cashback.show', $id)->with('error', 'Failed');
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $model = MstRedeemVoucher::find($id);
            $model->status = 'CANCELED';
            $model->updated_by = Auth::id();
            $model->update();
            DB::commit();
            return redirect()->route('cashback.show', $id)->with('success', 'Canceled');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('cashback.show', $id)->with('error', 'Failed');
        }
    }

    public function delete($id)
    {
        try {
            $model = MstRedeemVoucher::findOrFail($id);
            $model->update([
                'is_deleted' => 1
            ]);
            return redirect()->route('cashback.index')->with('success', 'Deleted');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    // -----------------------------
    // Term Condition
    // -----------------------------
    public function createTC(Request $request)
    {
        $this->validate(
            $request,
            [
                'tc_type' => 'required',
                'tc_coder' => 'required',
                'tc_label' => 'required',
                'tc_type_code' => 'required|unique:mst_redeem_term_conditions,code'
            ],
            [
                'tc_type.required' => 'Type cannot be blank',
                'tc_coder.required' => 'Code cannot be blank',
                'tc_label.required' => 'Label cannot be blank',
                'tc_type_code.unique' => 'Code already created'
            ]
        );

        DB::beginTransaction();
        try {
            $model = MstRedeemTermCondition::create([
                'code' => $request->tc_type_code,
                'label' => $request->tc_label,
                'min_amount' => $request->tc_min_amount,
                'min_tenor' => $request->tc_min_tenor,
                'date_start' => $request->tc_date_start != null ? date('Y-m-d', strtotime($request->tc_date_start)) : null,
                'date_end' => $request->tc_date_end != null ? date('Y-m-d', strtotime($request->tc_date_end)) : null,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            // $request->session()->flash('tc.data', $model);
            return json_encode([
                'code' => $model->code,
                'label' => $model->label,
                'min_amount' => $model->min_amount,
                'min_tenor' => $model->min_tenor,
                'date_start' => $model->date_start,
                'date_end' => $model->date_end,
                'data' => $model
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
        }
    }

    public function testPushNotif($voucher_id)
    {
        $device_ids = MstInvestor::select('device_id')
            ->where([
                'is_deleted' => 0,
                'active' => 1,
                'is_completed' => 1
            ])
            ->whereRaw("device_id <> '' ")
            ->whereNotNull('device_id')
            ->get();
        $uti = new Utility;
        $voucher = MstRedeemVoucher::findOrFail($voucher_id);
        if (!empty($device_ids)) {
            $devices = "[";
            $count = count($device_ids);
            $no = 1;
            foreach ($device_ids as $value) {
                $devices .= '"'.$value->device_id.'"';
                $devices .= $no == $count ? ']' : ',';
                $no++;
            }
            
            try {
                $message = '{
                    "title": "Ada cashback Rp '.number_format($voucher->amount, 0 , ',', '.').' untuk Anda!",
                    "body": "Cek voucher Anda untuk melihat cara mendapatkannya"
                }';
                $uti->sendAppNotif($devices, $message);
            } catch (\Throwable $th) {
                throw new Exception("Error Processing Request", 500);
            }
        }
    }

    public function redeemed($id)
    {
        $model = DB::table('trc_redeem')
                ->select(['mst_investor.full_name','trc_redeem.redeem_date','trc_redeem.amount','mst_investor.is_dummy'])
                ->join('mst_investor', 'trc_redeem.investor_id', 'mst_investor.id')
                ->where('redeem_voucher_id', $id)
                ->orderBy('trc_redeem.id', 'desc')
                ->get();

        return DataTables::of($model)
            ->editColumn('full_name', function($model){
                return $model->is_dummy == 1 ? $model->full_name.' <span class="label bg-gray">DUMMY</span>' : $model->full_name;
            })
            ->editColumn('amount', function($model){
                return number_format($model->amount, 0, ',', '.').' IDR';
            })
            ->editColumn('redeem_date', function($model){
                return date('d-m-Y H:i', strtotime($model->redeem_date));
            })
            ->addIndexColumn()
            ->rawColumns(['full_name','amount','redeem_date'])
            ->make(true);
    }


}
