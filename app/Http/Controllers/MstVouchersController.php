<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstVouchers;
use App\Models\MstVoucherInvestor;
use App\Models\MstAsset;
use App\Models\MstVoucherLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;
use Illuminate\Validation\Rule;

class MstVouchersController extends Controller
{
    const MODULE_NAME = 'Voucher Asset';

    public $mvouchers;

    public function __construct()
    {
        $this->mvouchers = new MstVouchers;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.vouchers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $model = new MstVouchers();

        $asset = DB::table('mst_asset as a')
                ->join('mst_asset_lang as c', 'c.asset_id', 'a.id')
                ->where('is_deleted','0')
                ->where('active','1')
                ->where('c.code','IND')
                ->pluck('c.asset_name', 'c.asset_id')
                ->all();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.vouchers.create', compact(['model','asset', 'language']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $request->validate([
            'code'          => 'required|string',
            'type'          => 'required',
            'value_type'    => 'required',
            'value'         => 'required',
            'asset_id'      => 'required',
            // 'time_of_use'   => 'required',
            'quota'         => 'required_if:type,PUBLIC',
            'date_start'    => 'required',
            'date_end'      => 'required',
            'name'          => 'required|string',
            'desc'          => 'required|string',
            'investor'      => 'required_if:type,PRIVATE',
            'image'         => 'file|image|mimes:jpg,jpeg,png,gif|max:5048'
        ]);

        $userId = Auth::user()->id;

        if($request->quota == '')
            $request->quota = 0;

        $data = [
            'code'  => $request->code,
            'type'  => $request->type,
            'value_type'  => $request->value_type,
            'value'  => $request->value,
            'asset_id'  => $request->asset_id,
            // 'time_of_use'  => $request->tim,
            'time_of_use'  => $request->time_of_use,
            'quota'  => $request->quota,
            'min_invest_amount'  => $request->min_invest_amount,
            'remain_quota'  => $request->quota,
            'date_start'  => date('Y-m-d', strtotime($request->date_start)),
            'date_end'  => date('Y-m-d', strtotime($request->date_end)),
            'status'  => 'DRAFT',
            'created_by' => $userId
        ];

        if($model = MstVouchers::create($data))
        {
            $data_lang = [
                'voucher_id'    => $model->id,
                'code'          => 'IND',
                'title'         => $request->name,
                'desc'          => $request->desc,
                'iframe'        => $request->iframe
            ];

            if($file = $request->file('image'))
            {
                $img_name = time().'_'.$file->getClientOriginalName();
                $path = $request->image->storeAs('voucher', $img_name, 'public-img');
                $data_lang = array_merge($data_lang, ['image' => $img_name]);
            }

            if($model2 = MstVoucherLang::create($data_lang))
            {
                if($file = $request->file('investor'))
                {
                    $file_name = time().'_'.$file->getClientOriginalName();
                    $file_investor = $request->investor->storeAs('voucher', $file_name, 'public-file');
                    $path = base_path().'/public/files/'.$file_investor;
                    $uti = new Utility;
                    $data_investor = $uti->csvToArray($path);
                    foreach($data_investor as $key => $value)
                    {
                        $investor = [
                            'voucher_id' => $model->id,
                            'id' => $value['ID'],
                            'used' => 'N'
                        ];
                        MstVoucherInvestor::create($investor);
                    }
                    $totalDataInvestor = count($data_investor);
                    DB::table('mst_voucher2')
                    ->where('id', $model->id)
                    ->update([
                        'quota' => $totalDataInvestor,
                        'remain_quota' => $totalDataInvestor
                    ]);
                }

                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/vouchers/'. base64_encode($model->id))->with('success', 'Created successfully');
            }
            else
                return redirect('master/security-guide')->with('error', 'Failed');
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstVoucherLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.vouchers.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $request->validate([
            'title'     => 'required|string',
            'desc'      => 'required|string',
            'voucher_id'   => 'required',
            'image'         => 'file|image|mimes:jpg,jpeg,png,gif|max:5048'
        ]);

        $userId = Auth::user()->id;

        $description = str_replace('<pre>', '<p>', $request->desc);
        $description = str_replace('</pre>', '</p>', $description);

        $data = [
            'voucher_id' => $request->voucher_id,
            'code' => $request->code,
            'title' => $request->title,
            'desc' => $description,
            'iframe' => $request->iframe
        ];

        if($file = $request->file('image'))
        {
            $img_name = time().'_'.$file->getClientOriginalName();
            $path = $request->image->storeAs('voucher', $img_name, 'public-img');
            $data = array_merge($data, ['image' => $img_name]);
        }

        if($model = MstVoucherLang::create($data))
        {
            /*$voucher = MstVouchers::findOrFail($model->voucher_id);
            if($voucher->isComplete())
            {
                $voucher->update([
                    'status'    => 'PUBLISHED',
                    'updated_by' => $userId,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }*/
            \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
            return redirect('master/vouchers/'. base64_encode($model->voucher_id))->with('success', 'Created successfully');
        }
        else
            return redirect('master/vouchers')->with('error', 'Failed');
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

        $id = base64_decode($id);

        $voucher = MstVouchers::findOrFail($id);

        $model = DB::table('mst_voucher2 as v')
                    ->join('mst_voucher_lang as vl','v.id','vl.voucher_id')
                    ->join('mst_asset_lang as al','v.asset_id','al.asset_id')
                    ->join('mst_asset as a','al.asset_id','a.id')
                    ->where('v.id', $id)
                    ->where('vl.code', 'IND')
                    ->where('al.code', 'IND')
                    ->select(
                        'vl.id as voucher_lang_id',
                        'vl.title',
                        'vl.desc',
                        'vl.image',
                        'vl.iframe',
                        'v.*',
                        'al.asset_name',
                        'a.owner_name'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.vouchers.detail', compact([
            'voucher', 
            'model', 
            'language', 
            'uti'
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
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstVouchers::findOrFail($id);

        // if($model->status != 'DRAFT')
        //     abort(401, 'Unauthorized action.');

        $asset = DB::table('mst_asset as a')
                ->join('mst_asset_lang as c', 'c.asset_id', 'a.id')
                ->where('is_deleted','0')
                ->where('active','1')
                ->where('c.code','IND')
                ->pluck('c.asset_name', 'c.asset_id')
                ->all();

        $asset_detail = MstAsset::where('id', $model->asset_id)->first();

        return view('master.vouchers.update', compact(['model','asset','asset_detail']));
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
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $request->validate([
            'code'          => 'required|string',
            'type'          => 'required',
            'value_type'    => 'required',
            'value'         => 'required',
            'asset_id'      => 'required',
            // 'time_of_use'   => 'required',
            'quota'         => 'required_if:type,PUBLIC',
            'date_start'    => 'required',
            'date_end'      => 'required',
            'investor'      => 'required_if:perbarui,Y'
        ]);

        $id = base64_decode($id);

        $userId = Auth::user()->id;

        $model = MstVouchers::findOrFail($id);

        if($request->quota == '')
            $request->quota = 0;

        $data = [
            'code'  => $request->code,
            'type'  => $request->type,
            'value_type'  => $request->value_type,
            'value'  => $request->value,
            'asset_id'  => $request->asset_id,
            'time_of_use'  => $request->time_of_use,
            'min_invest_amount'  => $request->min_invest_amount,
            'quota'  => $request->quota,
            'remain_quota'  => $request->quota,
            'date_start'  => date('Y-m-d', strtotime($request->date_start)),
            'date_end'  => date('Y-m-d', strtotime($request->date_end)),
            'updated_by' => $userId
        ];

        if($model->update($data))
        {
            // jika tipe ganti public maka hapus data investor
            if($request->type == 'PUBLIC')
            {
                DB::table('mst_voucher_pair')
                    ->where('voucher_id', $id)
                    ->delete();
            }

            if($file = $request->file('investor'))
            {
                // delete data sebelumnya
                DB::table('mst_voucher_pair')
                    ->where('voucher_id', $id)
                    ->delete();
                // upload data baru
                $file_name = time().'_'.$file->getClientOriginalName();
                $file_investor = $request->investor->storeAs('voucher', $file_name, 'public-file');
                $path = base_path().'/public/files/'.$file_investor;
                $uti = new Utility;
                $data_investor = $uti->csvToArray($path);
                foreach($data_investor as $key => $value)
                {
                    $investor = [
                        'voucher_id' => $model->id,
                        'id' => $value['ID'],
                        'used' => 'N'
                    ];
                    MstVoucherInvestor::create($investor);
                }
                $totalDataInvestor = count($data_investor);
                DB::table('mst_voucher2')
                ->where('id', $model->id)
                ->update([
                    'quota' => $totalDataInvestor,
                    'remain_quota' => $totalDataInvestor
                ]);
            }

            \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
            return redirect('master/vouchers/'. base64_encode($model->id))->with('success', 'Updated successfully');
        }
        else
            return redirect('master/vouchers')->with('error', 'Failed');
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $id = base64_decode($id);

        $model = MstVoucherLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.vouchers.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        $request->validate([
            'title'     => 'required',
            'desc'   => 'required',
            'image'         => 'file|image|mimes:jpg,jpeg,png,gif|max:5048'
        ]);

        $id = base64_decode($id);
        $model = MstVoucherLang::findOrFail($id);
        $userId = Auth::user()->id;
        $date = date('Y-m-d H:i:s');

        $description = str_replace('<pre>', '<p>', $request->desc);
        $description = str_replace('</pre>', '</p>', $description);

        $data = [
            'title' => $request->title,
            'desc' => $description,
            'iframe' => $request->iframe
        ];

        $path = base_path().'/public/images/voucher/';

        $unlink = false;
        if($model->image)
        {
            if($request->img == '' || $request->img == null)
            {
                // unlink($path.$model->image);
                $unlink = true;
                $data = array_merge($data, ['image'=>null]);
            }
        }            

        if($image = $request->file('image'))
        {
            $img_name = time().'_'.$image->getClientOriginalName();
            $path = $request->image->storeAs('voucher', $img_name, 'public-img');
            $data = array_merge($data, ['image' => $img_name]);
            // delete old image
            // if ($model->image && !$unlink)
                // unlink($path.$model->image);
        }

        if($model->update($data))
        {
            \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
            return redirect('master/vouchers/'. base64_encode($model->voucher_id))->with('success', 'Description updated');
        }
        else
            return redirect('master/vouchers')->with('error', 'Failed');
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

    public function cancel($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $data = [
            'status' => 'CANCELED',
            'updated_by' => Auth::user()->id
        ];
        $model = MstVouchers::findOrFail($id);
        if($model->update($data))
        {
            \UserLogActivity::addLog('Cancel '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/vouchers/'.base64_encode($id))->with('success', 'Deleted');
        }
        else
            return redirect('master/vouchers/'.base64_encode($id))->with('error', 'Failed');
    }

    public function publish($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        // check apakah bahasanya sudah lengkap
        if($this->mvouchers->isLangComplete($id))
        {
            $data = [
                'status' => 'PUBLISHED',
                'updated_by' => Auth::user()->id
            ];
            $model = MstVouchers::findOrFail($id);

            if($updated = $model->update($data))
            {
                // add user log activity
                \UserLogActivity::addLog('Publish '.self::MODULE_NAME.' ID #'.$id.' Successfully');
                // add notifikasi ke semua investor yang berhak mendapatkan voucher tersebut
                if($model->sendVoucherNotification($id))
                    return redirect('master/vouchers/'.base64_encode($id))->with('success', 'Published');
                else
                    return redirect('master/vouchers/'.base64_encode($id))->with('success', 'Published (Notification not sent)');
            }
            else
                return redirect('master/vouchers/'.base64_encode($id))->with('error', 'Failed');
        }
        else
            return redirect('master/vouchers/'.base64_encode($id))->with('error', 'You need to complete all language');

    }

    public function investor($id)
    {
        return view('master.vouchers.tab.investor');
    }

    public function dataTable()
    {
        $model = DB::select("
            select 
                v.*,
                vl.title,
                vl.desc,
                vl.image,
                vl.iframe,
                case 
                    when current_date() >= v.date_start and current_date() <= v.date_end then
                        'ACTIVE'
                    else
                        'INACTIVE'
                end active
            from 
                mst_voucher2 as v
            left join mst_voucher_lang as vl on v.id = vl.voucher_id and vl.code = 'IND'
            order by v.id desc;
        ");
        

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.vouchers.action', [
                    'model' => $model,
                    'url_show'=> route('vouchers.show', base64_encode($model->id) ),
                    'url_edit'=> route('vouchers.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('vouchers.destroy', base64_encode($model->id) )
                ]);
            })
            ->editColumn('active', function($model){
                $return = '';
                if($model->active == 'ACTIVE')
                    $return .= '<span class="label label-success">';
                else
                    $return .= '<span class="label label-danger">';

                $return .= $model->active.'</span>';
                return $return;
            })
            ->editColumn('status', function($model){
                $return = '';
                if($model->status == 'DRAFT')
                    $return .= '<span class="label label-warning">';
                elseif($model->status == 'PUBLISHED')
                    $return .= '<span class="label label-primary">';
                elseif($model->status == 'CANCELED')
                    $return .= '<span class="label label-danger">';
                else
                    $return .= '<span class="label label-default">';

                $return .= $model->status.'</span>';
                return $return;
            })
            ->editColumn('value', function($model){
                $return = '';
                if($model->value_type == 'NOMINAL')
                    $return = 'Rp.'.number_format($model->value);
                else
                    $return = $model->value.'%';
                return $return;
            })
            ->editColumn('type', function($model){
                $return = '';
                if($model->type == 'PUBLIC')
                    $return .= '<span class="label label-success">';
                elseif($model->type == 'PRIVATE')
                    $return .= '<span class="label label-danger">';

                $return .= $model->type.'</span>';
                return $return;
            })
            ->addIndexColumn()
            ->rawColumns(['action','active','status','value','type'])
            ->make(true);
    }

    public function dataTableInvestor($id)
    {
        $model = DB::select("
            select 
                vp.id,
                i.full_name,
                vp.used
            from 
                mst_voucher_pair as vp
            left join mst_investor as i on vp.id = i.id
            where 
                vp.voucher_id = ".$id."
        ");

        return DataTables::of($model)
            ->addIndexColumn()
            ->make(true);
    }

    public function getAsset($id)
    {
        $model = MstAsset::where('id', $id)->first();
        echo json_encode(['owner'=>$model->owner_name,'price'=> 'Rp '.number_format($model->price_market)]);
    }
}
