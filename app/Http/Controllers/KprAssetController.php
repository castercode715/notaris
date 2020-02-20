<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\KprAsset;
use App\Models\KprAssetImg;
use Yajra\DataTables\DataTables;
use App\Utility;
use DB;

class KprAssetController extends Controller
{
    public function index()
    {
        return view('kpr.asset.index');
    }

    public function create()
    {
        $uti = new utility();
        $model = new KprAsset();

        return view('kpr.asset.create', compact('model','uti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'              => 'required|string|unique:mst_kpr_asset,code,1,is_deleted|min:8',
            'name'              => 'required|string|unique:mst_kpr_asset,name,1,is_deleted|min:8',
            'location'          => 'required',
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
            'location'          => $request->location,
            'price'             => str_replace(",","", $request->price),
            'tenor'             => $request->tenor,
            'installment'       => str_replace(",","", $request->installment),
            'description'       => $request->description,
            'term_cond'         => $request->term_cond,
            'status'            => $request->status,
            'created_by'        => $userId,
            'updated_by'        => $userId
        ];

        $model = KprAsset::create($data);

        if($model){
            
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

            return redirect('kpr/asset/show/'.base64_encode($model->code))->with('success', 'Succesfully created!');

        }

    }

    public function show($id)
    {
        $id = base64_decode($id);
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

        $tahun = $model->tenor / 12 ;

        return view('kpr.asset.detail', compact(['model','tahun','featured','other','featuredCount','otherCount']));

    }

    public function data()
    {
        $model = DB::table('mst_kpr_asset as a')
                    ->where('a.is_deleted','<>','1')
                    ->orderBy('a.created_at','desc')
                    ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return '<a href="'.route('kpr.asset.edit', base64_encode($model->code)).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <a href="'.route('kpr.asset.show', base64_encode($model->code)).'" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></a>
                            <a href="'.route('kpr.asset.delete', base64_encode($model->code)).'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
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
                }else if($model->status == "B"){
                    $msg = "<span class='label label-info'>Booked</span>";
                }else{
                    $msg = "<span class='label label-danger'>Unpublished</span>";
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
}
