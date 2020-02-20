<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\KprAsset;
use App\Models\KprAssetImg;
use App\Models\MstCountries;
use App\Models\MstRegencies;
use App\Models\MstProvinces;
use Yajra\DataTables\DataTables;
use App\Utility;
use DB;

class KprController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new KprAsset;
        $published  = DB::table('mst_kpr_asset')->where('is_deleted', '0')->where('status','P')->count();
        $draft      = DB::table('mst_kpr_asset')->where('is_deleted', '0')->where('status','D')->count();
        $booked     = DB::table('mst_kpr_asset')->where('is_deleted', '0')->where('status','S')->count();
        $unpublish  = DB::table('mst_kpr_asset')->where('is_deleted', '0')->where('status','U')->count();

        return view('kpr.asset.index', compact('published','draft','booked','unpublish','model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uti = new utility();
        $model = new KprAsset();
        $countries = MstCountries::where('id','=','1')
                            ->get();

        return view('kpr.asset.create', compact('model','uti','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'              => 'required|string|unique:mst_kpr_asset,code,1,is_deleted|min:8',
            'name'              => 'required|string|unique:mst_kpr_asset,name,1,is_deleted|min:8',
            'regencies_id'      => 'required',
            'price'             => 'required',
            'tenor'             => 'required',
            'installment'       => 'required',
            'description'       => 'required',
            'term_cond'         => 'required',
            'status'            => 'required',
            'image.*'           => 'string',
        ]);

        // dd(str_replace(",","", $request->price));
        $userId = Auth::user()->id;
        $data = [
            'code'              => $request->code,
            'name'              => $request->name,
            'location'          => $request->regencies_id,
            'price'             => str_replace(",","", $request->price),
            'tenor'             => $request->tenor,
            'installment'       => str_replace(",","", $request->installment),
            'description'       => $request->description,
            'term_cond'         => $request->term_cond,
            'status'            => $request->status,
            'created_by'        => $userId,
            'updated_by'        => $userId
        ];

        /* Upload Featured Image */
        $featured = $request->featured;
        if($featured != null){

            $arrayData = [
                'code_kpr'      => $request->code,
                'image'         => $featured,
                'featured'      => 'Y',
            ];

            KprAssetImg::create($arrayData);
        }

        /* Upload Attribute Image */
        $images = $request->image;
        if($images != null){
            foreach ($images as $key) {
                # code...
                $AttrImages = [
                    'code_kpr'      => $request->code,
                    'image'         => $key,
                    'featured'      => 'N',
                ];

                KprAssetImg::create($AttrImages);
            }
        }

        $model = KprAsset::create($data);

        if($model){
            return redirect('kpr/asset/'.base64_encode($request->code) )->with('success','Asset Successfully Created');
        }else{
            Redirect::back()->withErrors(['error', 'Failed']);
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
        $id = base64_decode($id);

        // dd($id);
        $model = KprAsset::findOrFail($id);
        $featured = DB::table('mst_kpr_asset_img as a')
                    ->where('a.code_kpr',$id)
                    ->where('a.featured','Y')
                    ->first();

        $featuredCount = DB::table('mst_kpr_asset_img as a')
                    ->where('a.code_kpr',$id)
                    ->where('a.featured','Y')
                    ->count();

        $other = DB::table('mst_kpr_asset_img as a')
                    ->where('a.code_kpr',$id)
                    ->where('a.featured','<>','Y')
                    ->get();

        $otherCount = DB::table('mst_kpr_asset_img as a')
                    ->where('a.code_kpr',$id)
                    ->where('a.featured','<>','Y')
                    ->count();

        $location = DB::table('mst_regencies as a')
                        ->join('mst_regencies_lang as b','b.regencies_id','=','a.id')
                        ->join('mst_kpr_asset as c','c.location','=','b.regencies_id')
                        ->where('c.code',$id)
                        ->where('b.code','IND')
                        ->select('b.name as location')
                        ->first();



        $tahun = $model->tenor / 12 ;

        return view('kpr.asset.detail', compact(['model','tahun','featured','other','featuredCount','otherCount','location']));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = KprAsset::findOrFail($id);
        $uti = new utility();

        /* ----------- IMAGE FEATURED ----------- */
        $featured = DB::table('mst_kpr_asset_img as a')
                    ->where('a.code_kpr',$id)
                    ->where('a.featured','Y')
                    ->first();

        $other = DB::table('mst_kpr_asset_img as a')
                    ->where('a.code_kpr',$id)
                    ->where('a.featured','<>','Y')
                    ->get();

        /* ----------- LOCATION ----------- */
        $countriesList = MstCountries::where('id','=','1')->get();
        $provincesList = [];
        $regenciesList = [];
        $address = [];

        if($model->location != '')
        {
            $address = DB::table('mst_regencies as r')
                        ->join('mst_provinces as p','r.provinces_id','p.id')
                        ->join('mst_countries as c','p.countries_id','c.id')
                        ->where('r.id',$model->location)
                        ->select('r.id as regency','p.id as province','c.id as country')
                        ->first();

            $provincesList = MstProvinces::where('countries_id', $address->country)->get();
            $regenciesList = DB::table('mst_regencies as a')
                            ->join('mst_regencies_lang as b','b.regencies_id','=','a.id')
                            ->select('b.regencies_id','b.name')
                            ->where('a.provinces_id', $address->province)
                            ->where('b.code', 'IND')
                            ->get();
        }


        return view('kpr.asset.update', compact(
            'model',
            'uti',
            'featured',
            'address',
            'other',
            'countriesList', 
            'provincesList', 
            'countriesList', 
            'regenciesList'
        ));

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
            'code'              => 'required|string|unique:mst_kpr_asset,code,'.base64_decode($id).',code,is_deleted,0',
            'name'              => 'required|string|unique:mst_kpr_asset,name,'.base64_decode($id).',code,is_deleted,0',
            'regencies_id'      => 'required',
            'price'             => 'required',
            'tenor'             => 'required',
            'installment'       => 'required',
            'description'       => 'required',
            'term_cond'         => 'required',
            'status'            => 'required',
            'image.*'           => 'string',
        ]);

        $model = KprAsset::findOrFail(base64_decode($id));
        $userId = Auth::user()->id;

        $data = [
            'code'              => $request->code,
            'name'              => $request->name,
            'location'          => $request->regencies_id,
            'price'             => str_replace(",","", $request->price),
            'tenor'             => $request->tenor,
            'installment'       => str_replace(",","", $request->installment),
            'description'       => $request->description,
            'term_cond'         => $request->term_cond,
            'status'            => $request->status,
            'created_by'        => $userId,
            'updated_by'        => $userId
        ];

        /* Upload Featured Image */
        $featured = $request->featured;
        if($featured != null){

            $arrayData = [
                'code_kpr'      => $request->code,
                'image'         => $featured,
                'featured'      => 'Y',
            ];

            KprAssetImg::create($arrayData);
        }

        /* Upload Attribute Image */
        $images = $request->image;
        if($images != null){
            foreach ($images as $key) {
                # code...
                $AttrImages = [
                    'code_kpr'      => $request->code,
                    'image'         => $key,
                    'featured'      => 'N',
                ];

                KprAssetImg::create($AttrImages);
            }
        }

        $model->update($data);
        if($model){
            return redirect('kpr/asset/'.base64_encode($request->code) )->with('success','Asset Successfully Updated');
        }else{
            Redirect::back()->withErrors(['error', 'Failed']);
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
        $id = base64_decode($id);

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_kpr_asset set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where code = ".$id."");

        if($delete){
            $cek = KprAssetImg::where('code_kpr',$id)->delete();
        }

    }

    public function removeAttrImg(Request $request)
    {
        $asset_id  = $request->get('asset');
        $attr  = $request->get('attr');


        if($attr == "other"){

            $model = DB::table('mst_kpr_asset_img')
                ->where('id',$asset_id)
                ->where('featured','N')
                ->delete(); 
        }else{
            $model = DB::table('mst_kpr_asset_img')
                ->where('id',$asset_id)
                ->where('featured','Y')
                ->delete(); 
        }

         
    }

    public function delete($id)
    {

        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;
        $delete = DB::update("update mst_kpr_asset set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where code = ".$id."");

        if($delete){
            $cek = KprAssetImg::where('code_kpr',$id)->delete();
        }
        
        return redirect('kpr/asset')->with('success', 'Deleted');
       
    }

    public function dataTable()
    {
        $model = DB::table('mst_kpr_asset as a')
                    ->where('a.is_deleted','<>','1')
                    ->orderBy('a.created_at','desc')
                    ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return '<a href="'.route('asset.edit', base64_encode($model->code)).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <a href="'.route('asset.show', base64_encode($model->code)).'" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></a>
                            <a href="'.route('asset.destroy', base64_encode($model->code)).'" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
            })

            ->editColumn('installment', function($model){
                return number_format($model->installment)." IDR";
            })

            ->editColumn('status', function($model){
                $msg = "";
                if($model->status == "P"){
                    $msg = "<span class='label label-success'>Published</span>";
                }else if($model->status == "D"){
                    $msg = "<span class='label label-warning'>Draft</span>";
                }else if($model->status == "S"){
                    $msg = "<span class='label label-info'>Sold</span>";
                }else{
                    $msg = "<span class='label label-danger'>Unpublish</span>";
                }

                return $msg;
            })

            ->editColumn('booked_by', function($model){
                
                $result = new KprAsset;
                if($model->booked_by != null){
                    $msg = $result->booked();
                }else{
                    $msg = "-";
                }

                return $msg;
            })

            ->rawColumns(['action','installment','status','booked_by'])
            ->make(true);
    }

    public function upload(Request $request)
    {
        $file = $request->img;
        $filename = "kpr_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'kpr/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }

    public function uploadFeatured(Request $request)
    {
        $file = $request->img;
        $filename = "fkpr_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'kpr/featured/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }

    public function assetPane()
    {
        return view('kpr.asset.tab.detail');
    }

    public function investorPane()
    {
        return view('kpr.asset.tab.investor');
    }

    public function dataTableKprInv($id)
    {
        $model = DB::table('trc_kpr_booking as a')
                    ->join('mst_investor as b','b.id','=','a.investor_id')
                    ->join('mst_kpr_asset as c','c.code','=','a.code_kpr')
                    ->where('c.is_deleted','0')
                    ->where('b.is_deleted','0')
                    ->where('a.code_kpr', $id)
                    ->select('a.id','a.created_at as date_','c.code as code','b.full_name as investor_name', 'a.price as price_', 'a.installment as installment2','a.tenor as tenor_','a.status as status_')
                    ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return '<a href="'.route('kpr.investor.show-detail', base64_encode($model->id)).'" title="Detail Transaction" class="btn btn-show2 btn-sm btn-primary"><i class="fa fa-search"></i></a>';
            })

            ->editColumn('price_', function($model){
                return number_format($model->price_)." IDR";
            })

            ->editColumn('investor_name', function($model){
                return ucwords($model->investor_name);
            })

            ->editColumn('installment2', function($model){
                return number_format($model->installment2)." IDR";
            })

            ->editColumn('tenor_', function($model){
                return $model->tenor_." Month ";
            })

            ->editColumn('status_', function($model){
                $msg = "";
                if($model->status_ == "NEW"){
                    $msg = "<span class='label label-success'>NEW</span>";
                }else if($model->status_ == "ACCEPT"){
                    $msg = "<span class='label label-info'>ACCEPT</span>";
                }else if($model->status_ == "REJECT"){
                    $msg = "<span class='label label-warning'>REJECT</span>";
                }else if($model->status_ == "CANCEL"){
                    $msg = "<span class='label label-danger'>CANCEL</span>";
                }else{
                    $msg = "<span class='label label-default'>SURVEY</span>";
                }

                return $msg;
            })

            ->editColumn('date_', function($model){
                return date('d M Y H:i:s', strtotime($model->date_))." WIB";
            })

            ->rawColumns(['action','price_','installment2','tenor_','status_','date_','investor_name'])
            ->make(true);

    }

    public function show_detail($id)
    {
        $id = base64_decode($id);
        $model = DB::table('trc_kpr_booking as a')
                    ->join('mst_investor as b','b.id','=','a.investor_id')
                    ->join('mst_kpr_asset as c','c.code','=','a.code_kpr')
                    ->where('c.is_deleted','0')
                    ->where('b.is_deleted','0')
                    ->where('a.id', $id)
                    ->select('b.full_name', 'a.created_at as date_','c.name as kpr_name', 'a.status as status_', 'a.price as price_','a.installment as installment_','a.tenor as tenor_','a.surveyor as surveyor', 'a.surveyor_phone', 'a.survey_start_date', 'a.survey_end_date', 'a.note', 'a.approved_at', 'a.rejected_at')
                    ->first();

        return view('kpr.asset.tab.detail-action', compact('model'));
    }   
}
