<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstAptAssetModels; 
use App\Models\MstAptAssetImages; 
use App\Models\MstCountries; 
use App\Models\MstProvinces; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstAptAssetControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new MstAptAssetModels;
        return view('master.apt-asset.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstAptAssetModels();
        $uti = new utility();
        $countries = MstCountries::where('id','=','1')->get();

        return view('master.apt-asset.create', compact('model','uti','countries'));
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
            'code_apt'          => 'required|string|unique:mst_apt_asset,code_apt,1,is_deleted|max:2',
            'name'              => 'required|string|unique:mst_apt_asset,name,1,is_deleted|min:5',
            'regencies_id'      => 'required',
            'price'             => 'required',
            'tenor'             => 'required',
            'installment'       => 'required',
            'maintenance'       => 'required',
            'description'       => 'required',
            'term_cond'         => 'required',
            'status'            => 'required',
            'type_apt'          => 'required',
            'image.*'           => 'string',
        ]);

        $userId = Auth::user()->id;
        
        /* Upload Featured Image */
        $featured = $request->featured;
        if($featured != null){

            $arrayData = [
                'code_apt'      => $request->code_apt,
                'image'         => $featured,
                'featured'      => 'Y',
            ];

            MstAptAssetImages::create($arrayData);
        }

        /* Upload Attribute Image */
        $images = $request->image;
        if($images != null){
            foreach ($images as $key) {
                # code...
                $AttrImages = [
                    'code_apt'      => $request->code_apt,
                    'image'         => $key,
                    'featured'      => 'N',
                ];

                MstAptAssetImages::create($AttrImages);
            }
        }

        
        $file = $request->file;
        if($file != null){

            $data = [
                'code_apt'          => $request->code_apt,
                'name'              => $request->name,
                'location'          => $request->regencies_id,
                'price'             => str_replace(",","", $request->price),
                'tenor'             => $request->tenor,
                'installment'       => str_replace(",","", $request->installment),
                'maintenance'       => str_replace(",","", $request->maintenance),
                'description'       => $request->description,
                'term_cond'         => $request->term_cond,
                'type_apt'          => $request->type_apt,
                'file'              => $request->file,
                'status'            => $request->status,
                'created_by'        => $userId,
                'updated_by'        => $userId
            ];

        }else{

            $data = [
                'code_apt'          => $request->code_apt,
                'name'              => $request->name,
                'location'          => $request->regencies_id,
                'price'             => str_replace(",","", $request->price),
                'tenor'             => $request->tenor,
                'installment'       => str_replace(",","", $request->installment),
                'maintenance'       => str_replace(",","", $request->maintenance),
                'description'       => $request->description,
                'term_cond'         => $request->term_cond,
                'type_apt'          => $request->type_apt,
                'status'            => $request->status,
                'created_by'        => $userId,
                'updated_by'        => $userId
            ];

        }

        $model = MstAptAssetModels::create($data);

        if($model){
            return redirect('apt/asset/'.base64_encode($request->code_apt) )->with('success','Asset Successfully Created');
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
        $code_apt = base64_decode($id);
        $model = MstAptAssetModels::findOrFail($code_apt);

        $featured = DB::table('mst_apt_asset_img as a')
                    ->where('a.code_apt',$code_apt)
                    ->where('a.featured','Y')
                    ->get();

        $other = DB::table('mst_apt_asset_img as a')
                    ->where('a.code_apt',$code_apt)
                    ->where('a.featured','<>','Y')
                    ->get();

        $location = DB::table('mst_regencies as a')
                    ->join('mst_regencies_lang as b','b.regencies_id','=','a.id')
                    ->join('mst_apt_asset as c','c.location','=','b.regencies_id')
                    ->where('c.code_apt',$code_apt)
                    ->where('b.code','IND')
                    ->select('b.name as location')
                    ->first();

        $tahun = $model->tenor / 12 ;
        return view('master.apt-asset.detail', compact(['model','tahun','featured','other','location']));
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
        $model = MstAptAssetModels::findOrFail($id);
        $uti = new utility();

        /* ----------- IMAGE FEATURED ----------- */
        $featured = DB::table('mst_apt_asset_img as a')
                    ->where('a.code_apt',$id)
                    ->where('a.featured','Y')
                    ->get();

        // dd($featured);
        /* ----------- OTHER IMAGE ----------- */
        $other = DB::table('mst_apt_asset_img as a')
                    ->where('a.code_apt',$id)
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

        return view('master.apt-asset.update', compact(
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
            'code_apt'          => 'required|string|unique:mst_apt_asset,code_apt,'.base64_decode($id).',code_apt,is_deleted,0|max:2',
            'name'              => 'required|string|unique:mst_apt_asset,name,'.base64_decode($id).',code_apt,is_deleted,0',
            'regencies_id'      => 'required',
            'price'             => 'required',
            'tenor'             => 'required',
            'installment'       => 'required',
            'maintenance'       => 'required',
            'description'       => 'required',
            'term_cond'         => 'required',
            'status'            => 'required',
            'type_apt'          => 'required',
            'image.*'           => 'string',
        ]);

        $model = MstAptAssetModels::findOrFail(base64_decode($id));
        $userId = Auth::user()->id;

        /* Upload Featured Image */
        $featured = $request->featured;
        if($featured != null){

            $arrayData = [
                'code_apt'      => $request->code_apt,
                'image'         => $featured,
                'featured'      => 'Y',
            ];

            MstAptAssetImages::create($arrayData);
        }

        /* Upload Attribute Image */
        $images = $request->image;
        if($images != null){
            foreach ($images as $key) {
                # code...
                $AttrImages = [
                    'code_apt'      => $request->code_apt,
                    'image'         => $key,
                    'featured'      => 'N',
                ];

                MstAptAssetImages::create($AttrImages);
            }
        }

        $file = $request->file;
        if($file != null){

            $data = [
                'code_apt'          => $request->code_apt,
                'name'              => $request->name,
                'location'          => $request->regencies_id,
                'price'             => str_replace(",","", $request->price),
                'tenor'             => $request->tenor,
                'installment'       => str_replace(",","", $request->installment),
                'maintenance'       => str_replace(",","", $request->maintenance),
                'description'       => $request->description,
                'term_cond'         => $request->term_cond,
                'type_apt'          => $request->type_apt,
                'file'              => $request->file,
                'status'            => $request->status,
                'created_by'        => $userId,
                'updated_by'        => $userId
            ];

        }else{

            $data = [
                'code_apt'          => $request->code_apt,
                'name'              => $request->name,
                'location'          => $request->regencies_id,
                'price'             => str_replace(",","", $request->price),
                'tenor'             => $request->tenor,
                'installment'       => str_replace(",","", $request->installment),
                'maintenance'       => str_replace(",","", $request->maintenance),
                'description'       => $request->description,
                'term_cond'         => $request->term_cond,
                'type_apt'          => $request->type_apt,
                'status'            => $request->status,
                'created_by'        => $userId,
                'updated_by'        => $userId
            ];

        }

        $model->update($data);
        if($model){
            return redirect('apt/asset/'.base64_encode($request->code_apt) )->with('success','Asset Successfully Updated');
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

        $a = ['mst_floor_apt'];
        $b = ['code_apt'];

        if(!$this->isAllowedToUpdate2($a, $b, $id)){
            abort(400, 'Table has related.');
        }

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::table('mst_apt_asset')->where('code_apt', '=', $id)->delete();

        if($delete){
            $cek = MstAptAssetImages::where('code_apt',$id)->delete();
        }

    }

    public function delete($id)
    {
        $id = base64_decode($id);
        $a = ['mst_floor_apt'];
        $b = ['code_apt'];

        if(!$this->isAllowedToUpdate2($a, $b, $id)){
            abort(401, 'Table has related.');

        }

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::table('mst_apt_asset')->where('code_apt', '=', $id)->delete();

        if($delete){
            $cek = MstAptAssetImages::where('code_apt',$id)->delete();
        }
        
        return redirect('apt/asset')->with('success', 'Deleted');
    }

    public function dataTable()
    {
        $model = MstAptAssetModels::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.apt-asset.action', [
                    'model' => $model,
                    'url_show'=> route('asset.show', base64_encode($model->code_apt)),
                    'url_edit'=> route('asset.edit', base64_encode($model->code_apt)),
                    'url_destroy'=> route('asset.destroy', base64_encode($model->code_apt))
                ]);
            })

            ->editColumn('status', function($model){
                $msg = "";
                if($model->status == "ACTIVE"){
                    $msg = "<span class='label label-success'>ACTIVE</span>";
                }else if($model->status == "INACTIVE"){
                    $msg = "<span class='label label-danger'>INACTIVE</span>";
                }else{
                    $msg = "<span class='label label-default'>UNKNOWN</span>";
                }

                return $msg;
            })

            ->editColumn('type_apt', function($model){
                $msg = "";
                if($model->type_apt == "STUDIO 21"){
                    $msg = "<span class='label label-default'>STUDIO 21</span>";
                }else if($model->type_apt == "STUDIO 24"){
                    $msg = "<span class='label label-warning'>STUDIO 24</span>";
                }else{
                    $msg = "<span class='label label-danger'>UNKNOWN</span>";
                }

                return $msg;
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by','status','type_apt'])
            ->make(true);
    }

    public function dataTableUnit($id)
    {
        $model = DB::table('mst_apt_asset as a')
                    ->join('mst_unit_apt as b', 'b.code_apt', '=', 'a.code_apt')
                    ->where('a.is_deleted','0')
                    ->where('b.is_deleted','0')
                    ->where('b.code_apt', $id)
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

            ->editColumn('status', function($model){
                $msg = "";
                if($model->status == "ACTIVE"){
                    $msg = "<span class='label label-success'>ACTIVE</span>";
                }else if($model->status == "INACTIVE"){
                    $msg = "<span class='label label-warning'>INACTIVE</span>";
                }else{
                    $msg = "<span class='label label-danger'>UNKNOWN</span>";
                }

                return $msg;
            })

            ->editColumn('type_apt', function($model){
                $msg = "";
                if($model->type_apt == "STUDIO 21"){
                    $msg = "<span class='label label-default'>STUDIO 21</span>";
                }else if($model->type_apt == "STUDIO 24"){
                    $msg = "<span class='label label-warning'>STUDIO 24</span>";
                }else{
                    $msg = "<span class='label label-danger'>UNKNOWN</span>";
                }

                return $msg;
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by','status','type_apt'])
            ->make(true);
    }

    /**
     * Another FILE UPLOAD *
    */
    public function denah(Request $request)
    {
        $file = $request->img;
        $filename = "_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/apt-asset/denah/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }

    public function file_asset(Request $request)
    {
        $file = $request->img;
        $filename = "file_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/apt-asset/file/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }

    public function featured(Request $request)
    {
        $file = $request->img;
        $filename = "featured_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/apt-asset/img/featured/';

        $file->move($move_path,$filename);
        return $move_path.$filename;

        // dd($request->code_apt);
    }

    public function images(Request $request)
    {
        $file = $request->img;
        $filename = "img_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/apt-asset/img/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }

    /**
     * END FILE UPLOAD *
    */

    public function assetPane()
    {
        return view('master.apt-asset.tab.detail');
    }

    public function assetUnitPane($id)
    {
        $model = MstAptAssetModels::findOrFail($id);
        return view('master.apt-asset.floor.index', compact('model'));
    }

    public function removeAttrFile(Request $request)
    {
        $asset_id  = $request->get('asset');
        $attr  = $request->get('attr');

        if($attr == "file-attachment"){

            $model = DB::update("update mst_apt_asset set 
                    file = null
                    where code_apt = ".$asset_id.""); 

            echo json_encode(array("status" => TRUE));
        }else{
            echo json_encode(array("status" => FALSE));
        }
    }

    public function removeAttrImage(Request $request)
    {
        $asset_id  = $request->get('asset');
        $attr  = $request->get('attr');
        $id  = $request->get('id');

        if($attr == "featured"){

            $model = DB::table('mst_apt_asset_img')
                        ->where('code_apt',$asset_id)
                        ->where('featured','Y')
                        ->where('id',$id)
                        ->delete();

                if($model){
                    echo json_encode(array("status" => TRUE));
                }else{
                    echo json_encode(array("status" => FALSE));
                }

        }else if($attr == "other"){

            $model = DB::table('mst_apt_asset_img')
                        ->where('code_apt',$asset_id)
                        ->where('featured','N')
                        ->where('id',$id)
                        ->delete();

                if($model){
                    echo json_encode(array("status" => TRUE));
                }else{
                    echo json_encode(array("status" => FALSE));
                }

        }else{
            echo json_encode(array("status" => FALSE));
        }
    }


}
