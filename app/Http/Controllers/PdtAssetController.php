<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdtAsset;
use App\Models\PdtAssetPhoto;
use App\Models\PdtCategoryAsset;
use App\Models\PdtAssetAttribute;
use App\Models\PdtAssetAttrValue;
use App\Models\MstTermConds;
use App\Models\PdtAssetRating;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class PdtAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.asset.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new PdtAsset();

        $category = PdtCategoryAsset::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('desc', 'id')
                    ->all();

        $termsconds = MstTermConds::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('title', 'id')
                    ->all();

        $tenorCredit = $model->tenorCreditList();
        $tenorInvestment = $model->tenorInvestmentList();

        return view('master.asset.create', compact([
            'model',
            'category',
            'termsconds',
            'tenorCredit',
            'tenorInvestment'
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
        $this->validate($request, [
            'asset_name' => 'required|string',
            'category_asset_id' => 'required',
            'desc' => 'required|string',
            'price_njop' => 'required',
            'price_market' => 'required',
            'credit_tenor' => 'required|string',
            'invesment_tenor' => 'required|string',
            'terms_conds_id' => 'required',
            'file_resume' => 'required',
            'images' => 'required',
            'owner_name' => 'required|string',
            'owner_ktp_number' => 'required|string',
            'owner_kk_number' => 'required|string',
            // 'attr_name' => 'required|array|min:2',
            'featured.*' => 'required|distinct|min:1',
            'attr_name.*' => 'required|distinct|min:1',
            'attr_value.*' => 'required',
            'banner_new' => 'required',
            'banner_hot' => 'required',
        ]);

        // var_dump($request->featured);
        // die();

        $month = date('Y-m');
        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;
        // upload file resume pdf
        $pdf_file = $request->file('file_resume');
        $pdf_filename = time().$pdf_file->getClientOriginalName();
        $pdf_path = base_path().'/public/files/asset/'.$month.'/';
        $pdf_file->move($pdf_path, $pdf_filename);
        $file_resume = $month.'/'.$pdf_filename;
        // save asset
        $data = [
            'category_asset_id' => $request->category_asset_id,
            'asset_name' => $request->asset_name,
            'desc' => $request->desc,
            'owner_name' => $request->owner_name,
            'owner_ktp_number' => $request->owner_ktp_number,
            'owner_kk_number' => $request->owner_kk_number,
            'price_njop' => $request->price_njop,
            'price_market' => $request->price_market,
            'credit_tenor' => $request->credit_tenor,
            'invesment_tenor' => $request->invesment_tenor,
            'terms_conds_id' => $request->terms_conds_id,
            'created_by' => $userId,
            'updated_by' => $userId,
            'file_resume' => $file_resume
            // 'images' => serialize($img_array)
        ];

        $model = PdtAsset::create($data);
        if($model)
        {
            /*
            featured = 1
            banner hot = 2
            banner new = 3
            else = 0
            */
            // upload file banner hot {kode = 2}
            if($bannerhot_file = $request->file('banner_hot'))
            {
                // $bannerhot_file = $request->file('banner_hot');
                $bannerhot_filename = time().$bannerhot_file->getClientOriginalName();
                $bannerhot_path = base_path().'/public/images/asset/'.$month.'/';
                $bannerhot_file->move($bannerhot_path, $bannerhot_filename);
                $bannerhot = $month.'/'.$bannerhot_filename;
                $data_img = [
                    'asset_id'  => $model->id,
                    'photo_uri' => $bannerhot,
                    'desc'      => '-',
                    'featured'  => '2',
                    'created_by'=> $userId,
                    'updated_by'=> $userId
                ];
                PdtAssetPhoto::create($data_img);
            }
            // upload file banner new {kode = 3}
            if($bannernew_file = $request->file('banner_new'))
            {
                $bannernew_filename = time().$bannernew_file->getClientOriginalName();
                $bannernew_path = base_path().'/public/images/asset/'.$month.'/';
                $bannernew_file->move($bannernew_path, $bannernew_filename);
                $bannernew = $month.'/'.$bannernew_filename;
                $data_img = [
                    'asset_id'  => $model->id,
                    'photo_uri' => $bannernew,
                    'desc'      => '-',
                    'featured'  => '3',
                    'created_by'=> $userId,
                    'updated_by'=> $userId
                ];
                PdtAssetPhoto::create($data_img);
            }

            // upload untuk asset detail
            if($images = $request->file('images'))
            {
                foreach($images as $key => $image)
                {
                    $img_name = time().$image->getClientOriginalName();
                    $path = base_path().'/public/images/asset/'.$month.'/';
                    $image->move($path, $img_name);
                    $img_path = $month.'/'.$img_name;
                    $featured = 0;
                    if( (int)$key == (int)$request->featured[0] )
                        $featured = 1;
                    $data_img = [
                        'asset_id'  => $model->id,
                        'photo_uri' => $img_path,
                        'desc'      => '-',
                        'featured'  => $featured,
                        'created_by'=> $userId,
                        'updated_by'=> $userId
                    ];
                    $img = PdtAssetPhoto::create($data_img);
                }
            }

            $attr_name  = $request->attr_name;
            $attr_value = $request->attr_value;

            foreach($attr_name as $key => $value)
            {
                $attributes = [ 
                    'asset_id'=>$model->id,
                    'attr_asset_id' => $value, 
                    'value' => $attr_value[$key] 
                ];
                $attr = PdtAssetAttrValue::create($attributes);
            }

            return redirect('master/asset/'.$model->id)->with('success','Success');
        }
        else
            Redirect::back()->withErrors(['error', 'Failed']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = PdtAsset::findOrFail($id);
/*        // TAB ASSET
        $model = DB::table('pdt_asset as a')
                ->join('pdt_category_asset as b','a.category_asset_id','=','b.id')
                ->join('mst_terms_conds as c','a.terms_conds_id','=','c.id')
                ->select('a.*',
                    'b.desc as category',
                    'c.title as terms_conds'
                )
                ->where('a.id', $id)
                ->first();

        $images = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->whereIn('a.featured',['1','0'])
                ->orderBy('featured','desc')
                ->get();

        $hotbanner = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','2')
                ->first();

        $newbanner = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','3')
                ->first();

        $attributes = DB::table('pdt_asset_attr_value as a')
                    ->join('pdt_asset_attribute as c','a.attr_asset_id','=','c.id')
                    ->select('a.*','c.name as attr_name')
                    ->where('a.asset_id', $id)
                    ->get();
        // TAB RATING
        $rating = DB::table('pdt_asset_rating as a')
                ->select('a.id','b.full_name','b.photo','a.rating','a.review','a.created_at')
                ->join('mst_investor as b','a.investor_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->orderBy('a.created_at','desc')
                ->get();
*/

        return view('master.asset.detail', compact([
            'model'
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
        $model = PdtAsset::findOrFail($id);

        $category = PdtCategoryAsset::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('desc', 'id')
                    ->all();

        $termsconds = MstTermConds::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('title', 'id')
                    ->all();

        $tenorCredit = $model->tenorCreditList();
        $tenorInvestment = $model->tenorInvestmentList();

        $images = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->whereIn('a.featured',['1','0'])
                ->select('a.id','a.asset_id','a.photo_uri','a.featured')
                ->get();

        $attributes = DB::table('pdt_asset_attr_value as a')
                    ->join('pdt_asset_attribute as c','a.attr_asset_id','=','c.id')
                    ->select('a.*','c.name as attr_name')
                    ->where('a.asset_id', $id)
                    ->get();

        $attributeList = PdtAssetAttribute::where('active','1')
                        ->where('is_deleted','0')
                        ->where('category_asset_id',$model->category_asset_id)
                        ->pluck('name','id')
                        ->all();

        $hotbanner = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','2')
                ->first();

        $newbanner = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','3')
                ->first();

        return view('master.asset.update', compact([
            'model',
            'category',
            'termsconds',
            'tenorCredit',
            'tenorInvestment',
            'images',
            'attributes',
            'attributeList',
            'hotbanner',
            'newbanner',
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
        $this->validate($request, [
            'asset_name' => 'required|string',
            'category_asset_id' => 'required',
            'desc' => 'required|string',
            'price_njop' => 'required',
            'price_market' => 'required',
            'credit_tenor' => 'required|string',
            'invesment_tenor' => 'required|string',
            'terms_conds_id' => 'required',
            // 'images' => 'required',
            'owner_name' => 'required|string',
            'owner_ktp_number' => 'required|string',
            'owner_kk_number' => 'required|string',
            // 'attr_name' => 'required|array|min:2',
            'attr_name.*' => 'required|distinct|min:1',
            'attr_value.*' => 'required',
        ]);

        $model = PdtAsset::findOrFail($id);
        $month = date('Y-m');
        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'category_asset_id' => $request->category_asset_id,
            'asset_name' => $request->asset_name,
            'desc' => $request->desc,
            'owner_name' => $request->owner_name,
            'owner_ktp_number' => $request->owner_ktp_number,
            'owner_kk_number' => $request->owner_kk_number,
            'price_njop' => $request->price_njop,
            'price_market' => $request->price_market,
            'credit_tenor' => $request->credit_tenor,
            'invesment_tenor' => $request->invesment_tenor,
            'terms_conds_id' => $request->terms_conds_id,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        if($request->file('file_resume'))
        {
            // upload file resume pdf
            $pdf_file = $request->file('file_resume');
            $pdf_filename = time().$pdf_file->getClientOriginalName();
            $pdf_path = base_path().'/public/files/asset/'.$month.'/';
            $pdf_file->move($pdf_path, $pdf_filename);
            $file_resume = $month.'/'.$pdf_filename;
            
            $data = array_merge($data, ['file_resume'=>$file_resume]);
        }

        if($model->update($data))
        {
            // upload file banner hot {kode = 2}
            if($bannerhot_file = $request->file('banner_hot'))
            {
                // delete old hot banner imager
                $photo = PdtAssetPhoto::where('asset_id',$model->id)
                        ->where('featured','2')
                        ->first();
                unlink(base_path().'/public/images/asset/'.$photo->photo_uri);
                $photo->delete();
                // $bannerhot_file = $request->file('banner_hot');
                $bannerhot_filename = time().$bannerhot_file->getClientOriginalName();
                $bannerhot_path = base_path().'/public/images/asset/'.$month.'/';
                $bannerhot_file->move($bannerhot_path, $bannerhot_filename);
                $bannerhot = $month.'/'.$bannerhot_filename;
                $data_img = [
                    'asset_id'  => $model->id,
                    'photo_uri' => $bannerhot,
                    'desc'      => '-',
                    'featured'  => '2',
                    'created_by'=> $userId,
                    'updated_by'=> $userId
                ];
                PdtAssetPhoto::create($data_img);
            }
            // upload file banner new {kode = 3}
            if($bannernew_file = $request->file('banner_new'))
            {
                // delete old new banner imager
                $photo = PdtAssetPhoto::where('asset_id',$model->id)
                        ->where('featured','3')
                        ->first();
                unlink(base_path().'/public/images/asset/'.$photo->photo_uri);
                $photo->delete();

                $bannernew_filename = time().$bannernew_file->getClientOriginalName();
                $bannernew_path = base_path().'/public/images/asset/'.$month.'/';
                $bannernew_file->move($bannernew_path, $bannernew_filename);
                $bannernew = $month.'/'.$bannernew_filename;
                $data_img = [
                    'asset_id'  => $model->id,
                    'photo_uri' => $bannernew,
                    'desc'      => '-',
                    'featured'  => '3',
                    'created_by'=> $userId,
                    'updated_by'=> $userId
                ];
                PdtAssetPhoto::create($data_img);
            }

            if($images = $request->file('images'))
            {
                foreach($images as $key => $image)
                {
                    $img_name = time().$image->getClientOriginalName();
                    $path = base_path().'/public/images/asset/'.$month.'/';
                    $image->move($path, $img_name);
                    $img_path = $month.'/'.$img_name;

                    $featured = 0;
                    if($request->featured != null)
                    {
                        if( (int)$key == (int)$request->featured )
                        {
                            // update old featured image
                            PdtAssetPhoto::where('asset_id',$model->id)
                                        ->where('featured','1')
                                        ->update(['featured'=>'0']);

                            $featured = 1;
                        }
                    }

                    $data_img = [
                        'asset_id'  => $model->id,
                        'photo_uri' => $img_path,
                        'desc'      => '-',
                        'featured'  => $featured,
                        'created_by'=> $userId,
                        'updated_by'=> $userId
                    ];
                    
                    PdtAssetPhoto::create($data_img);
                }
            }

            $attr_name  = $request->attr_name;
            $attr_value = $request->attr_value;

            PdtAssetAttrValue::where('asset_id',$model->id)->delete();
            foreach($attr_name as $key => $value)
            {
                $attributes = [ 
                    'asset_id'=>$model->id,
                    'attr_asset_id' => $value, 
                    'value' => $attr_value[$key] 
                ];
                $attr = PdtAssetAttrValue::create($attributes);
            }

            return redirect('master/asset/'. $model->id)->with('success','Asset Updated');
        }
        else
            Redirect::back()->withErrors(['error', 'Failed']);
    }

    public function assetPane($id)
    {
        sleep(0.5);
        $model = DB::table('pdt_asset as a')
                ->join('pdt_category_asset as b','a.category_asset_id','=','b.id')
                ->join('mst_terms_conds as c','a.terms_conds_id','=','c.id')
                ->select('a.*',
                    'b.desc as category',
                    'c.title as terms_conds'
                )
                ->where('a.id', $id)
                ->first();

        $images = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->whereIn('a.featured',['1','0'])
                ->orderBy('featured','desc')
                ->get();

        $hotbanner = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','2')
                ->first();

        $newbanner = DB::table('pdt_asset_photo as a')
                ->join('pdt_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','3')
                ->first();

        $attributes = DB::table('pdt_asset_attr_value as a')
                    ->join('pdt_asset_attribute as c','a.attr_asset_id','=','c.id')
                    ->select('a.*','c.name as attr_name')
                    ->where('a.asset_id', $id)
                    ->get();
        
        $uti = new Utility;

        return view('master.asset.tab.asset', compact([
            'model',
            'images',
            'attributes',
            'uti',
            'hotbanner',
            'newbanner'
        ]));
    }

    public function ratingPane($id)
    {
        sleep(1);
        $model = PdtAsset::findOrFail($id);
        $rating = DB::table('pdt_asset_rating as a')
                ->select('a.id','b.full_name','b.photo','a.rating','a.review','a.created_at')
                ->join('mst_investor as b','a.investor_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->orderBy('a.created_at','desc')
                ->get();
        return view('master.asset.tab.rating', compact(['model','rating']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update pdt_asset set 
            deleted_date = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        if($delete)
            return redirect('master/asset')->with('success', 'Deleted');
        else
            return redirect('master/asset/show/'.$id)->with('error', 'Failed');
    }

    public function destroy($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update pdt_asset set 
            deleted_date = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function removeImage(Request $request)
    {
        $value  = $request->get('value');
        $model = PdtAssetPhoto::findOrFail($value);
        unlink(base_path().'/public/images/asset/'.$model->photo_uri);
        $model->delete();
    }

    public function removeAttr(Request $request)
    {
        $asset_id  = $request->get('asset');
        $attr_id  = $request->get('attr');
        $model = DB::table('pdt_asset_attr_value')
                ->where('asset_id',$asset_id)
                ->where('attr_asset_id',$attr_id)
                ->delete();
    }

    public function setFeatured(Request $request)
    {
        $id     = $request->get('id');
        $asset  = $request->get('asset');
        // update featured 1 menjadi 0
        PdtAssetPhoto::where('asset_id',$asset)
                    ->where('featured','1')
                    ->update(['featured'=>'0']);
        // set new featured image
        PdtAssetPhoto::where('id',$id)
                    ->update(['featured'=>'1']);
    }

    public function dataTable()
    {
        $model = PdtAsset::query();
        $model->where('is_deleted','<>','1');

        // $model = DB::table('pdt_asset as a')
        //         ->join('pdt_category_asset as b','a.category_asset_id','=','b.id')
        //         ->where('a.is_deleted','0')
        //         ->select('a.id as id','a.asset_name as asset_name','b.name as category','a.owner_name as owner_name','a.created_by as created_by','a.created_at as created_at')
        //         ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.asset.action', [
                    'model' => $model,
                    'url_show'=> route('asset.show', $model->id),
                    'url_edit'=> route('asset.edit', $model->id),
                    'url_destroy'=> route('asset.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->editColumn('category', function($model){
                $category = PdtCategoryAsset::findOrFail($model->category_asset_id);
                return $category->desc;
            })
            // ->addColumn('card_type', function($model){
            //     return $model->getCardType();
            // })
            // ->addColumn('image_logo', function($model){
            //     return "<img src='".'/images/bank/'.$model->image_logo."' width='100px' />";
            // })
            ->addIndexColumn()
            ->rawColumns(['action', 'category', 'create_at', 'created_by'])
            ->make(true);
    }

    public function fetchAttributes(Request $request)
    {
        $value  = $request->get('value');

        $data = DB::table('pdt_asset_attribute')
                ->where('category_asset_id','=',$value)
                ->where('is_deleted','0')
                ->where('active','1')
                ->get();
        
        $return = "<option value=''>- Select -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->id."'>".$row->name."</option>";
        }
        echo $return;
    }

}
