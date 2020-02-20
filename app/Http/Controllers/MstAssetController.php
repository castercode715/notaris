<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstAsset;
use App\Models\MstAssetLang;
use App\Models\MstClass;
use App\Models\MstAssetPhoto;
use App\Models\MstAssetCategory;
use App\Models\MstAssetAttribute;
use App\Models\MstAssetAttrValue;
use App\Models\MstAssetComment;
use App\Models\MstAssetFavorite;
use App\Models\MstTermsConds;
use App\Models\MstAssetRating;
use App\Models\MstLanguage;
use App\Models\MstCountries;
use App\Models\MstProvinces;
use App\Models\MstRegencies;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class MstAssetController extends Controller
{
    const MODULE_NAME = 'Data Asset';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.asset.index');
    }

/* ---------------------------------------
CREATE
-----------------------------------------*/
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $model = new MstAsset();

        $category = MstAssetCategory::join('mst_asset_category_lang','mst_asset_category.id','mst_asset_category_lang.asset_category_id')
                    ->where('mst_asset_category_lang.code','IND')
                    ->where('mst_asset_category.active','1')
                    ->where('mst_asset_category.is_deleted','0')
                    ->pluck('mst_asset_category_lang.description','mst_asset_category.id')
                    ->all();

        $class = MstClass::join('mst_class_lang','mst_class.id','mst_class_lang.class_id')
                    ->where('mst_class_lang.code','IND')
                    ->where('mst_class.active','1')
                    ->where('mst_class.is_deleted','0')
                    ->pluck('mst_class_lang.description','mst_class.id')
                    ->all();

        $termsconds = MstTermsConds::join('mst_terms_conds_lang','mst_terms_conds.id','mst_terms_conds_lang.terms_conds_id')
                    ->where('mst_terms_conds_lang.code','IND')
                    ->where('mst_terms_conds.active','1')
                    ->where('mst_terms_conds.is_deleted','0')
                    ->where('mst_terms_conds.view','Private')
                    ->pluck('mst_terms_conds_lang.title','mst_terms_conds.id')
                    ->all();

        $language = MstLanguage::where('code','IND')->first();

        $tenorCredit = $model->tenorCreditList();
        $countries = MstCountries::All();

        return view('master.asset.create', compact([
            'model',
            'category',
            'termsconds',
            'tenorCredit',
            'class',
            'countries',
            'language'
        ]));
    }

    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'asset_name' => 'required|string',
            // 'desc' => 'required|string',
            // 'file_resume' => 'required|file|mimes:pdf|max:5048',
            // 'file_fiducia' => 'required|file|mimes:pdf|max:5048',

            'category_asset_id' => 'required',
            'terms_conds_id' => 'required',
            'class_id' => 'required',
            // 'owner_name' => 'required|string',
            // 'owner_ktp_number' => 'required|string',
            // 'owner_kk_number' => 'required|string',
            // 'owner_npwp_number' => 'required|string',
            'date_available' => 'required',
            'date_expired' => 'required',
            'price_market' => 'required',
            'price_liquidation' => 'required',
            'price_loan' => 'required',
            'price_selling' => 'required',
            'credit_tenor' => 'required|string',
            'interest' => 'required',
            // 'regencies_id' => 'required',
            // 'country' => 'required',
            // 'province' => 'required',       
            // 'attr_name.*' => 'required|distinct|min:1',
            // 'attr_value.*' => 'required',
            // 'images' => 'required',
            // 'banner_new' => 'required|image|mimes:jpg,jpeg,png',
            // 'banner_hot' => 'required|image|mimes:jpg,jpeg,png',
            // 'featured_img' => 'required|image|mimes:jpg,jpeg,png',
            // 'featured.*' => 'required|distinct|min:1',
        ]);

        if($validate)
        {
            $month = date('Y-m');
            $userId = Auth::user()->id;
            // $active = $request->get('active') ? 1 : 0;
            
            $dataParent = [
                'category_asset_id' => $request->category_asset_id,
                'terms_conds_id' => $request->terms_conds_id,
                'class_id' => $request->class_id,
                'owner_name' => $request->owner_name,
                'owner_ktp_number' => $request->owner_ktp_number,
                'owner_kk_number' => $request->owner_kk_number,
                'owner_npwp_number' => $request->owner_npwp_number,
                'date_available' => date('Y-m-d', strtotime($request->date_available)),
                'date_expired' => date('Y-m-d', strtotime($request->date_expired)),
                'price_market' => str_replace(',', '', $request->price_market),
                'price_liquidation' => str_replace(',', '', $request->price_liquidation),
                'price_loan' => str_replace(',', '', $request->price_loan),
                'price_selling' => str_replace(',', '', $request->price_selling),
                'credit_tenor' => $request->credit_tenor,
                'interest' => $request->interest,
                'regencies_id' => $request->regencies_id,
                'active' => 0,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

            // dd($dataParent);

            if($model = MstAsset::create($dataParent))
            {
                $dataLang = [
                    'asset_id'  => $model->id,
                    'code'  => $request->code,
                    'asset_name'  => $request->asset_name,
                    'description'  => $request->desc
                ];

                $pdf_path = base_path().'/public/files/asset/'.$month.'/';
                // upload file resume pdf
                if($pdf_file = $request->file('file_resume'))
                {
                    $pdf_filename = 'RSM'.time().rand(10,99).'_IND_'.$pdf_file->getClientOriginalName();
                    $pdf_file->move($pdf_path, $pdf_filename);
                    $file_resume = $month.'/'.$pdf_filename;
                    $dataLang = array_merge($dataLang, ['file_resume'=>$file_resume]);
                }
                
                // upload file fiducia pdf
                if($fiducia_file = $request->file('file_fiducia'))
                {
                    $fiducia_filename = 'FDC'.time().rand(10,99).'_IND_'.$fiducia_file->getClientOriginalName();
                    $fiducia_file->move($pdf_path, $fiducia_filename);
                    $file_fiducia = $month.'/'.$fiducia_filename;
                    $dataLang = array_merge($dataLang, ['file_fiducia'=>$file_fiducia]);
                }

                if($assetlang = MstAssetLang::create($dataLang))
                {
                    if($featuredimg_file = $request->file('featured_img'))
                    {
                        $featuredimg_filename = 'FTD'.time().rand(10,99).'_'.$featuredimg_file->getClientOriginalName();
                        $featuredimg_path = base_path().'/public/images/asset/'.$month.'/';
                        $featuredimg_file->move($featuredimg_path, $featuredimg_filename);
                        $featuredimg = $month.'/'.$featuredimg_filename;
                        $data_img = [
                            'asset_id'  => $model->id,
                            'photo'     => $featuredimg,
                            'description' => 'Featured Image',
                            'featured'  => 1,
                            'active'    => 1,
                            'created_by'=> $userId,
                            'updated_by'=> $userId
                        ];
                        MstAssetPhoto::create($data_img);
                    }

                    if($bannerhot_file = $request->file('banner_hot'))
                    {
                        $bannerhot_filename = 'HOT'.time().rand(10,99).'_'.$bannerhot_file->getClientOriginalName();
                        $bannerhot_path = base_path().'/public/images/asset/'.$month.'/';
                        $bannerhot_file->move($bannerhot_path, $bannerhot_filename);
                        $bannerhot = $month.'/'.$bannerhot_filename;
                        $data_img = [
                            'asset_id'  => $model->id,
                            'photo'     => $bannerhot,
                            'description'      => 'Hot banner',
                            'featured'  => 2,
                            'active'    => 1,
                            'created_by'=> $userId,
                            'updated_by'=> $userId
                        ];
                        MstAssetPhoto::create($data_img);
                    }

                    if($bannernew_file = $request->file('banner_new'))
                    {
                        $bannernew_filename = 'NEW'.time().rand(10,99).'_'.$bannernew_file->getClientOriginalName();
                        $bannernew_path = base_path().'/public/images/asset/'.$month.'/';
                        $bannernew_file->move($bannernew_path, $bannernew_filename);
                        $bannernew = $month.'/'.$bannernew_filename;
                        $data_img = [
                            'asset_id'  => $model->id,
                            'photo'     => $bannernew,
                            'description'      => 'New Banner',
                            'featured'  => 3,
                            'active'    => 1,
                            'created_by'=> $userId,
                            'updated_by'=> $userId
                        ];
                        MstAssetPhoto::create($data_img);
                    }

                    if($images = $request->file('images'))
                    {
                        foreach($images as $key => $image)
                        {
                            $img_name = 'IMG'.time().rand(10,99).'_'.$image->getClientOriginalName();
                            $path = base_path().'/public/images/asset/'.$month.'/';
                            $image->move($path, $img_name);
                            $img_path = $month.'/'.$img_name;

                            $data_img = [
                                'asset_id'  => $model->id,
                                'photo'     => $img_path,
                                'description'      => '-',
                                'featured'  => 0,
                                'active'    => 1,
                                'created_by'=> $userId,
                                'updated_by'=> $userId
                            ];
                            MstAssetPhoto::create($data_img);
                        }
                    }

                    if($request->attr_name[0] != '')
                    {
                        $attr_name  = $request->attr_name;
                        $attr_value = $request->attr_value;

                        foreach($attr_name as $key => $value)
                        {
                            $attributes = [ 
                                'asset_id'=>$model->id,
                                'attr_asset_id' => $value, 
                                'value' => $attr_value[$key],
                                'active' => '1',
                                'created_by' => $userId,
                                'updated_by' => $userId
                            ];
                            MstAssetAttrValue::create($attributes);
                        }
                    }
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/asset/'.base64_encode($model->id) )->with('success','Asset Successfully Created');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstAssetLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.asset.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        $validate = $this->validate($request, [
            'asset_name' => 'required|string',
            // 'desc' => 'required|string',
            // 'file_resume' => 'required|file|mimes:pdf|max:5048',
            // 'file_fiducia' => 'required|file|mimes:pdf|max:5048',
        ]);

        if($validate)
        {
            $month = date('Y-m');
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->desc);
            $description = str_replace('</pre>', '</p>', $description);
            $data = [
                'asset_id'  => $request->asset_id,
                'code'  => $request->code,
                'asset_name'  => $request->asset_name,
                'description'  => $description
            ];

            $pdf_path = base_path().'/public/files/asset/'.$month.'/';

            if($pdf_file = $request->file('file_resume'))
            {
                $pdf_filename = 'RSM'.time().rand(10,99).'_'.$request->code.'_'.$pdf_file->getClientOriginalName();
                $pdf_file->move($pdf_path, $pdf_filename);
                $file_resume = $month.'/'.$pdf_filename;
                $data = array_merge($data, ['file_resume'=>$file_resume]);
            }
            
            // upload file fiducia pdf
            if($fiducia_file = $request->file('file_fiducia'))
            {
                $fiducia_filename = 'FDC'.time().rand(10,99).'_'.$request->code.'_'.$fiducia_file->getClientOriginalName();
                $fiducia_file->move($pdf_path, $fiducia_filename);
                $file_fiducia = $month.'/'.$fiducia_filename;
                $data = array_merge($data, ['file_fiducia'=>$file_fiducia]);
            }

            if($model = MstAssetLang::create($data))
            {
                /*if($model->isLanguageComplete())
                {
                    $asset = MstAsset::findOrFail($model->asset_id);
                    $asset->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }*/
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/asset/'.base64_encode($model->asset_id) )->with('success','New Language Successfully Added');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }

/* ---------------------------------------
EDIT
-----------------------------------------*/
    public function edit($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $a = ['mst_asset_attr_value','mst_contact','mst_voucher','trc_cart'];
        $b = ['asset_id','asset_id','asset_id','asset_id'];

        $id = base64_decode($id);

        // if(!$this->isAllowedToUpdate($a, $b, $id))
        //     abort(401, 'Table has been related.');


        $model = MstAsset::findOrFail($id);

        $category = MstAssetCategory::join('mst_asset_category_lang','mst_asset_category.id','mst_asset_category_lang.asset_category_id')
                    ->where('mst_asset_category_lang.code','IND')
                    ->where('mst_asset_category.active','1')
                    ->where('mst_asset_category.is_deleted','0')
                    ->pluck('mst_asset_category_lang.description','mst_asset_category.id')
                    ->all();

        $class = MstClass::join('mst_class_lang','mst_class.id','mst_class_lang.class_id')
                    ->where('mst_class_lang.code','IND')
                    ->where('mst_class.active','1')
                    ->where('mst_class.is_deleted','0')
                    ->pluck('mst_class_lang.description','mst_class.id')
                    ->all();

        $termsconds = MstTermsConds::join('mst_terms_conds_lang','mst_terms_conds.id','mst_terms_conds_lang.terms_conds_id')
                    ->where('mst_terms_conds_lang.code','IND')
                    ->where('mst_terms_conds.active','1')
                    ->where('mst_terms_conds.is_deleted','0')
                    ->where('mst_terms_conds.view','Private')
                    ->pluck('mst_terms_conds_lang.title','mst_terms_conds.id')
                    ->all();

        $tenorCredit = $model->tenorCreditList();

        /* ----------- ATTRIBUTES ----------- */
        $attributes = DB::table('mst_asset_attr_value as a')
                    ->join('mst_asset_attribute as b','a.attr_asset_id','b.id')
                    ->join('mst_asset_attribute_lang as c','b.id','c.asset_attribute_id')
                    ->where('a.asset_id', $id)
                    ->where('c.code','IND')
                    ->select('a.attr_asset_id','a.value','a.asset_id')
                    ->get();

        $attributeList = DB::table('mst_asset_attribute as a')
                ->join('mst_asset_attribute_lang as b','a.id','b.asset_attribute_id')
                ->where('a.category_asset_id',$model->category_asset_id)
                ->where('a.is_deleted','0')
                ->where('a.active','1')
                ->where('b.code','IND')
                ->select('a.id','b.description')
                ->get();

        /* ----------- IMAGES ----------- */
        $images = DB::table('mst_asset_photo as a')
                ->join('mst_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->whereIn('a.featured',['0'])
                ->select('a.id','a.asset_id','a.photo','a.featured')
                ->get();

        $featured = DB::table('mst_asset_photo')
                    ->where('asset_id', $id)
                    ->where('active','1') 
                    ->where('is_deleted','0') 
                    ->where('featured','1') 
                    ->first();

        $hotbanner = DB::table('mst_asset_photo as a')
                ->join('mst_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','2')
                ->first();

        $newbanner = DB::table('mst_asset_photo as a')
                ->join('mst_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','3')
                ->first();

        /* ----------- ADDRESS ----------- */
        $countriesList = MstCountries::all();
        $provincesList = [];
        $regenciesList = [];
        $address = [];

        if($model->regencies_id != '')
        {
            $address = DB::table('mst_regencies as r')
                        ->join('mst_provinces as p','r.provinces_id','p.id')
                        ->join('mst_countries as c','p.countries_id','c.id')
                        ->where('r.id',$model->regencies_id)
                        ->select('r.id as regency','p.id as province','c.id as country')
                        ->first();

            $provincesList = MstProvinces::where('countries_id', $address->country)->get();
            $regenciesList = MstRegencies::where('provinces_id', $address->province)->get();
        }


        $uti = new Utility();
        /*$address = $uti->getDetailAddress($model->villages_id);
        $countries = DB::table('mst_countries')->get();
        $provinces = DB::table('mst_provinces')->where('countries_id',$address['country_id'])->get();
        $regencies = DB::table('mst_regencies')->where('provinces_id',$address['province_id'])->get();
        $districts = DB::table('mst_districs')->where('regencies_id',$address['regency_id'])->get();
        $villages = DB::table('mst_villages')->where('districs_id',$address['district_id'])->get();*/

        return view('master.asset.update', compact([
            'model',
            'category',
            'class',
            'termsconds',
            'tenorCredit',
            'attributes',
            'attributeList',
            'images',
            'featured',
            'hotbanner',
            'newbanner',
            'address',
            'countriesList', 
            'provincesList', 
            'regenciesList',
            'uti'
        ]));
    }

    public function update(Request $request, $id)
    {
        $validate = $this->validate($request, [
            'category_asset_id' => 'required',
            'class_id' => 'required',
            'terms_conds_id' => 'required',
            'price_market' => 'required',
            'price_liquidation' => 'required',
            'price_selling' => 'required',
            'price_loan' => 'required',
            'interest' => 'required',
            'credit_tenor' => 'required|string',
            // 'attr_name.*' => 'required|distinct|min:1',
            // 'attr_value.*' => 'required',
            // 'image_other.*' => 'required',
            // 'country' => 'required',
            // 'province' => 'required',
            // 'regencies_id' => 'required',
            // 'owner_name' => 'required|string',
            // 'owner_ktp_number' => 'required|string',
            // 'owner_kk_number' => 'required|string',
            // 'owner_npwp_number' => 'required|string',
            'date_available' => 'required',
            'date_expired' => 'required'
            // 'active' => 'required'
        ]);

        if($validate)
        {
            $model = MstAsset::findOrFail($id);
            $month = date('Y-m');
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'category_asset_id' => $request->category_asset_id,
                'terms_conds_id' => $request->terms_conds_id,
                'class_id' => $request->class_id,
                'owner_name' => $request->owner_name,
                'owner_ktp_number' => $request->owner_ktp_number,
                'owner_kk_number' => $request->owner_kk_number,
                'owner_npwp_number' => $request->owner_npwp_number,
                'date_available' => date('Y-m-d', strtotime($request->date_available)),
                'date_expired' => date('Y-m-d', strtotime($request->date_expired)),
                'price_market' => str_replace(',', '', $request->price_market),
                'price_liquidation' => str_replace(',', '', $request->price_liquidation),
                'price_loan' => str_replace(',', '', $request->price_loan),
                'price_selling' => str_replace(',', '', $request->price_selling),
                'credit_tenor' => $request->credit_tenor,
                'interest' => $request->interest,
                'regencies_id' => $request->regencies_id,
                'updated_by' => $userId
            ];

            if($model->update($data))
            {
                /* --------------------- ATTRIBUTES -------------------*/
                if( !empty($request->attr_name) && !empty($request->attr_value) )
                {
                    /*
                    * get attribute yang sudah ada
                    */
                    $attributeExisted = DB::table('mst_asset_attr_value')
                                            ->where('asset_id', $model->id)
                                            ->get();
                    /*
                    * hapus attribute yang tidak ada di form
                    * update value attribute yang sudah ada
                    */
                    foreach($attributeExisted as $attExisted)
                    {
                        if( !in_array($attExisted->attr_asset_id, $request->attr_name) )
                        {
                            /* 
                            * hapus untuk attribute yang tidak ada di form update
                            */
                            DB::table('mst_asset_attr_value')
                                ->where('asset_id', $attExisted->asset_id)
                                ->where('attr_asset_id', $attExisted->attr_asset_id)
                                ->delete();
                        }
                        else
                        {
                            /* 
                            * Update value untuk attribute yang sudah ada
                            */
                            $key = array_search($attExisted->attr_asset_id, $request->attr_name);
                            DB::table('mst_asset_attr_value')
                                ->where('asset_id', $attExisted->asset_id)
                                ->where('attr_asset_id', $attExisted->attr_asset_id)
                                ->update([
                                    'value' => $request->attr_value[$key],
                                    'updated_by' => $userId,
                                    'updated_at' => $date
                                ]);
                        }
                    }
                    /*
                    * get attribute yang sudah ada
                    */
                    $attributeExisted = DB::table('mst_asset_attr_value')
                                            ->where('asset_id', $model->id)
                                            ->get();
                    $attributeExistedArray = [];
                    foreach($attributeExisted as $value)
                    {
                        $attributeExistedArray[] = $value->attr_asset_id;
                    }
                    /* 
                    * insert attribute baru jika ada
                    */
                    foreach($request->attr_name as $key => $value)
                    {
                        if( !in_array($value, $attributeExistedArray) )
                        {
                            DB::table('mst_asset_attr_value')
                            ->insert([
                                'asset_id' => $model->id,
                                'attr_asset_id' => $value,
                                'value' => $request->attr_value[$key],
                                'active' => 1,
                                'created_by' => $userId,
                                'created_at' => $date,
                                'updated_by' => $userId,
                                'updated_at' => $date
                            ]);
                            
                        }
                    }
                }
                /* --------------------- END - ATTRIBUTES -------------------*/
                /* --------------------- IMAGES -------------------*/
                /*
                * Delete image yang dihapus di form update
                */
                if( !empty($request->image_other) )
                {
                    $imageExisted = DB::table('mst_asset_photo')
                                    ->where('asset_id', $model->id)
                                    ->where('featured', 0)
                                    ->get();

                    foreach($imageExisted as $imgExisted)
                    {
                        if( !in_array($imgExisted->id, $request->image_other) )
                        {
                            // unlink(base_path().'/public/images/asset/'.$imgExisted->photo);
                            MstAssetPhoto::findOrFail($imgExisted->id)->delete();
                        }
                    }
                }
                else
                {
                    $imageExisted = DB::table('mst_asset_photo')
                                    ->where('asset_id', $model->id)
                                    ->where('featured', 0)
                                    ->get();

                    foreach($imageExisted as $imgExisted)
                    {
                        // unlink(base_path().'/public/images/asset/'.$imgExisted->photo);
                        MstAssetPhoto::findOrFail($imgExisted->id)->delete();
                    }
                }
                /*
                * Upload image baru jika ada
                */
                if( $images = $request->file('images') )
                {
                    foreach($images as $image)
                    {
                        $img_name = 'IMG'.time().rand(10,99).'_'.$image->getClientOriginalName();
                        $path = base_path().'/public/images/asset/'.$month.'/';
                        $image->move($path, $img_name);
                        $img_path = $month.'/'.$img_name;

                        $data_img = [
                            'asset_id'  => $model->id,
                            'photo'     => $img_path,
                            'description'      => '-',
                            'featured'  => 0,
                            'active'    => 1,
                            'created_by'=> $userId,
                            'updated_by'=> $userId
                        ];
                        
                        MstAssetPhoto::create($data_img);
                    }
                }
                /*
                * Upload featured image baru jika ada
                */
                if( $featuredImg = $request->file('featured_img') )
                {
                    /* get current featured image */
                    $currentFeaturedImg = DB::table('mst_asset_photo')
                                            ->where('asset_id', $model->id)
                                            ->where('featured', 1)
                                            ->first();
                    /* delete current featured image */
                    if($currentFeaturedImg)
                    {
                        unlink(base_path().'/public/images/asset/'.$currentFeaturedImg->photo);
                        DB::table('mst_asset_photo')
                                ->where('asset_id', $model->id)
                                ->where('featured', 1)
                                ->delete();
                    }
                    /* upload new featured image */
                    $featured_filename = 'FTD'.time().rand(10,99).'_1_'.$featuredImg->getClientOriginalName();
                    $featured_path = base_path().'/public/images/asset/'.$month.'/';
                    $featuredImg->move($featured_path, $featured_filename);
                    $featured = $month.'/'.$featured_filename;

                    $data_img = [
                        'asset_id'  => $model->id,
                        'photo'     => $featured,
                        'description'      => 'Featured Image',
                        'featured'  => 1,
                        'active'    => 1,
                        'created_by'=> $userId,
                        'updated_by'=> $userId
                    ];
                    
                    MstAssetPhoto::create($data_img);
                }
                /*
                * Upload new banner baru jika ada
                */
                if( $bannerNew = $request->file('banner_new') )
                {
                    /* get current new banner */
                    $currentBannerNew = DB::table('mst_asset_photo')
                                            ->where('asset_id', $model->id)
                                            ->where('featured', 3)
                                            ->first();
                    /* delete current new banner */
                    if($currentBannerNew)
                    {
                        unlink(base_path().'/public/images/asset/'.$currentBannerNew->photo);
                        DB::table('mst_asset_photo')
                            ->where('asset_id', $model->id)
                            ->where('featured', 3)
                            ->delete();
                    }
                    /* upload new banner */
                    $banner_filename = 'NEW'.time().rand(10,99).'_'.$bannerNew->getClientOriginalName();
                    $banner_path = base_path().'/public/images/asset/'.$month.'/';
                    $bannerNew->move($banner_path, $banner_filename);
                    $banner = $month.'/'.$banner_filename;

                    $data_img = [
                        'asset_id'  => $model->id,
                        'photo'     => $banner,
                        'description'      => 'Banner New Image',
                        'featured'  => 3,
                        'active'    => 1,
                        'created_by'=> $userId,
                        'updated_by'=> $userId
                    ];
                    
                    MstAssetPhoto::create($data_img);
                }
                /*
                * Upload hot banner baru jika ada
                */
                if( $bannerHot = $request->file('banner_hot') )
                {
                    /* get current new banner */
                    $currentBannerHot = DB::table('mst_asset_photo')
                                            ->where('asset_id', $model->id)
                                            ->where('featured', 2)
                                            ->first();
                    /* delete current new banner */
                    if($currentBannerHot)
                    {
                        unlink(base_path().'/public/images/asset/'.$currentBannerHot->photo);
                        DB::table('mst_asset_photo')
                            ->where('asset_id', $model->id)
                            ->where('featured', 2)
                            ->delete();
                    }
                    /* upload new banner */
                    $banner_filename = 'HOT'.time().rand(10,99).'_'.$bannerHot->getClientOriginalName();
                    $banner_path = base_path().'/public/images/asset/'.$month.'/';
                    $bannerHot->move($banner_path, $banner_filename);
                    $banner = $month.'/'.$banner_filename;

                    $data_img = [
                        'asset_id'  => $model->id,
                        'photo'     => $banner,
                        'description'      => 'Banner Hot Image',
                        'featured'  => 2,
                        'active'    => 1,
                        'created_by'=> $userId,
                        'updated_by'=> $userId
                    ];
                    
                    MstAssetPhoto::create($data_img);
                }
                /* --------------------- END - IMAGES -------------------*/
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/asset/'. base64_encode($model->id))->with('success','Asset Updated');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }

    public function editNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstAssetLang::findOrFail($id);

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.asset.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'asset_name' => 'required|string',
            // 'desc' => 'required|string',
            // 'file_resume' => 'file|mimes:pdf|max:5048',
            // 'file_fiducia' => 'file|mimes:pdf|max:5048',
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $month = date('Y-m');
            $userId = Auth::user()->id;
            $model = MstAssetLang::findOrFail($id);

            $data = [
                'asset_name'    => $request->asset_name,
                'description'    => $request->desc
            ];

            $pdf_path = base_path().'/public/files/asset/'.$month.'/';

            if($pdf_file = $request->file('file_resume'))
            {
                $pdf_filename = 'RSM'.time().'-'.$request->code.'-'.$pdf_file->getClientOriginalName();
                $pdf_file->move($pdf_path, $pdf_filename);
                $file_resume = $month.'/'.$pdf_filename;
                $data = array_merge($data, ['file_resume'  => $file_resume]);
            }

            if($fiducia_file = $request->file('file_fiducia'))
            {
                $fiducia_filename = 'FDC'.time().'-'.$request->code.'-'.$fiducia_file->getClientOriginalName();
                $fiducia_file->move($pdf_path, $fiducia_filename);
                $file_fiducia = $month.'/'.$fiducia_filename;
                $data = array_merge($data, ['file_fiducia'  => $file_fiducia]);
            }

            if($model->update($data)){
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/asset/'.base64_encode($model->asset_id) )->with('success','Updated');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }
/* ---------------------------------------
DETAIL
-----------------------------------------*/
    public function show($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $model = MstAsset::findOrFail($id);

        return view('master.asset.detail', compact([
            'model'
        ]));
    }

    public function assetPane($id)
    {
        sleep(0.5);
        $id = base64_decode($id);

        /*$model = DB::table('mst_asset as a')
                    ->join('mst_asset_lang AS al','a.id','al.asset_id')
                    ->join('mst_language AS l','al.CODE','l.CODE')
                    ->join('mst_asset_category AS ac','a.category_asset_id','ac.id')
                    ->join('mst_asset_category_lang AS acl','ac.id','acl.asset_category_id')
                    ->join('mst_class AS c','a.class_id','c.id')
                    ->join('mst_class_lang AS cl','c.id','cl.class_id')
                    ->join('mst_terms_conds AS tc','a.terms_conds_id','tc.id')
                    ->join('mst_terms_conds_lang AS tcl','tc.id','tcl.terms_conds_id')
                    ->join('mst_regencies as r','a.regencies_id','r.id')
                    ->join('mst_regencies_lang as rl','r.id','rl.regencies_id')
                    ->join('mst_provinces as p','r.provinces_id','p.id')
                    ->join('mst_countries as co','p.countries_id','co.id')
                    ->where('al.CODE','IND')
                    ->where('acl.CODE','IND')
                    ->where('cl.CODE','IND')
                    ->where('tcl.CODE','IND')
                    ->where('rl.CODE','IND')
                    ->where('a.id', $id)
                    ->select(
                        'l.LANGUAGE',
                        'acl.description AS category',
                        'cl.description AS class',
                        'tcl.title AS terms_conds',
                        'al.id AS asset_lang_id',
                        'al.asset_name',
                        'al.description',
                        'al.file_resume',
                        'al.file_fiducia',
                        'a.*',
                        'rl.name as regency',
                        'p.name as province',
                        'co.name as country'
                    )
                    ->first();*/
        $model = DB::table('mst_asset as a')
                    ->join('mst_asset_lang AS al','a.id','al.asset_id')
                    ->join('mst_language AS l','al.CODE','l.CODE')
                    ->join('mst_asset_category AS ac','a.category_asset_id','ac.id')
                    ->join('mst_asset_category_lang AS acl','ac.id','acl.asset_category_id')
                    ->join('mst_class AS c','a.class_id','c.id')
                    ->join('mst_class_lang AS cl','c.id','cl.class_id')
                    ->join('mst_terms_conds AS tc','a.terms_conds_id','tc.id')
                    ->join('mst_terms_conds_lang AS tcl','tc.id','tcl.terms_conds_id')
                    ->where('al.CODE','IND')
                    ->where('acl.CODE','IND')
                    ->where('cl.CODE','IND')
                    ->where('tcl.CODE','IND')
                    ->where('a.id', $id)
                    ->select(
                        'l.LANGUAGE',
                        'acl.description AS category',
                        'cl.description AS class',
                        'tcl.title AS terms_conds',
                        'al.id AS asset_lang_id',
                        'al.asset_name',
                        'al.description',
                        'al.file_resume',
                        'al.file_fiducia',
                        'a.*'
                    )
                    ->first();

        $country = '';
        $province = '';
        $regency = '';

        if($model->regencies_id != '')
        {
            $r = DB::table('mst_regencies as a')
                ->join('mst_regencies_lang as b', 'a.id', 'b.regencies_id')
                ->join('mst_provinces as c', 'a.provinces_id', 'c.id')
                ->join('mst_countries as d', 'c.countries_id', 'd.id')
                ->where('regencies_id', $model->regencies_id)
                ->where('code', 'IND')
                ->select('b.name as regency', 'c.name as province', 'd.name as country')
                ->first();

            $regency = $r->regency;
            $province = $r->province;
            $country = $r->country;
        }

        $featured = DB::table('mst_asset_photo')
                        ->where('asset_id', $id)
                        ->where('featured','1')
                        ->first();

        $images = DB::table('mst_asset_photo as a')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->whereIn('a.featured',['0'])
                ->orderBy('a.featured','desc')
                ->get();

        $hotbanners = DB::table('mst_asset_photo as a')
                ->join('mst_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','2')
                ->first();

        $newbanners = DB::table('mst_asset_photo as a')
                ->join('mst_asset as b','a.asset_id','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->where('a.featured','3')
                ->first();

        $attributes = DB::table('mst_asset_attr_value as av')
                    ->join('mst_asset_attribute AS a','av.attr_asset_id','a.id')
                    ->join('mst_asset_attribute_lang AS al','a.id','al.asset_attribute_id')
                    ->select('al.description','av.value')
                    ->where("al.CODE",'IND')
                    ->where('av.asset_id',$id)
                    ->get();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();
        
        $uti = new Utility;

        $asset = new MstAsset;

        return view('master.asset.tab.asset', compact([
            'model',
            'images',
            'featured',
            'attributes',
            'uti',
            'hotbanners',
            'newbanners',
            'language',
            'asset',
            'country',
            'province',
            'regency'
        ]));
    }

    public function ratingPane($id)
    {
        // sleep(0.5);
        $id = base64_decode($id);
        $model = MstAsset::findOrFail($id);
        $rating = DB::table('mst_asset_rating as a')
                ->select('a.id','b.full_name','b.photo','a.rating','a.review','a.created_at')
                ->join('mst_investor as b','a.created_by','=','b.id')
                ->where('a.asset_id',$id)
                ->where('a.active','1')
                ->where('a.is_deleted','0')
                ->orderBy('a.created_at','desc')
                ->get();

        return view('master.asset.tab.rating', compact(['model','rating']));
    }

    public function commentPane($id)
    {
        $id = base64_decode($id);

        $model = DB::table('mst_asset_comment as a')
                ->join('mst_investor as b','a.created_by','b.id')
                ->where('asset_id', $id)
                ->where('a.active', 1)
                ->where('a.is_deleted', 0)
                ->select('b.full_name','a.comment','a.created_at')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('master.asset.tab.comment', compact('model'));
    }

    public function favoritePane($id)
    {
        $id = base64_decode($id);

        $count = DB::table('mst_asset_favorite')
                ->where('asset_id', $id)
                ->where('is_deleted', 0)
                ->count();

        $model = DB::table('mst_asset_favorite as a')
                ->join('mst_investor as b','a.investor_id','b.id')
                ->where('asset_id', $id)
                ->where('a.active', 1)
                ->where('a.is_deleted', 0)
                ->select('b.full_name','a.comment','a.created_at')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('master.asset.tab.favorite', compact(['model','count']));
    }

    public function investorPane($id)
    {
        $id = base64_decode($id);

        $uti = new Utility;
        $rate = $uti->USDIDRRate();

        $model = MstAsset::findOrFail($id);
        
        /* get total investment */
        $totalInvestmentIdr = DB::select("select get_total_investment_per_asset_n(?) as total_idr", [$id])[0]->total_idr;
        $totalInvestmentUsd = DB::select("select get_total_investment_per_asset(?, 'usd') as total_usd", [$id])[0]->total_usd;
        // $totalInvestment = $totalInvestmentIdr + ($totalInvestmentUsd * $rate);
        $totalInvestment = $totalInvestmentIdr;

        // dd($totalInvestmentIdr);
        
        /* sisa investasi */
        $remaining = $model->price_loan - $totalInvestment;
        $remainingPrecentage = $totalInvestment / $model->price_loan * 100;

        /* total investment */
        $totalInvestor = DB::table('trc_asset_investor as ai')
                            ->where('asset_id', $id)
                            ->where('status', "ACTIVE")
                            ->count();

        $data = DB::select("
            select 
                i.full_name as investor_name,
                ai.currency_code as currency,
                ai.invest_tenor,
                ai.number_interest,
                ai.amount,
                ai.date_start,
                ai.date_end,
                (select f_sum_interest(ai.amount, ai.number_interest, ai.invest_tenor, 8)) as daily_interest
            from 
            trc_asset_investor as ai
            left join trc_transaction_asset as ta on ta.id = ai.trc_asset_id
            join mst_investor as i on ta.investor_id = i.id
            where 
            ai.asset_id = ".$id."
            and ai.status = 'ACTIVE'
        ");

        return view('master.asset.tab.investor', compact([
            'data',
            'model',
            'totalInvestment',
            'remaining',
            'remainingPrecentage',
            'totalInvestor'
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_asset set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/asset')->with('success', 'Deleted');
        }
        else
            return redirect('master/asset/show/'.$id)->with('error', 'Failed');
    }

    public function destroy($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_asset set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function removeImage(Request $request)
    {
        $value  = $request->get('value');
        $model = MstAssetPhoto::findOrFail($value);
        $data = [
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => Auth::user()->id,
            'is_deleted' => '0'
        ];
        $model->update($data);
        // unlink(base_path().'/public/images/asset/'.$model->photo);
        // $model->delete();
    }

    public function removeAttr(Request $request)
    {
        $asset_id  = $request->get('asset');
        $attr_id  = $request->get('attr');
        $model = DB::table('mst_asset_attr_value')
                ->where('asset_id',$asset_id)
                ->where('attr_asset_id',$attr_id)
                ->delete();
    }

    public function setFeatured(Request $request)
    {
        $id     = $request->get('id');
        $asset  = $request->get('asset');
        // update featured 1 menjadi 0
        MstAssetPhoto::where('asset_id',$asset)
                    ->where('featured','1')
                    ->update(['featured'=>'0']);
        // set new featured image
        MstAssetPhoto::where('id',$id)
                    ->update(['featured'=>'1']);
    }

/* ---------------------------------------
FETCH
-----------------------------------------*/
    public function fetchAttributes(Request $request)
    {
        $value  = $request->get('value');

        $data = DB::table('mst_asset_attribute as a')
                ->join('mst_asset_attribute_lang as b','a.id','b.asset_attribute_id')
                ->where('a.category_asset_id',$value)
                ->where('a.is_deleted','0')
                ->where('a.active','1')
                ->where('b.code','IND')
                ->select('a.id','b.description')
                ->get();
        
        $return = "<option value=''>- Select -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->id."'>".$row->description."</option>";
        }
        echo $return;
    }

/* ---------------------------------------
DATA TABLE
-----------------------------------------*/
    public function dataTable() { 
        $model = DB::table('mst_asset as a') 
                    ->join('mst_asset_category as b','a.category_asset_id','=','b.id') 
                    ->join('mst_asset_category_lang as c','b.id','=','c.asset_category_id') 
                    ->join('mst_asset_lang as d','a.id','=','d.asset_id') 
                    ->join('mst_class_lang as e', 'a.class_id', '=', 'e.class_id') 
                    ->where('e.code','IND') 
                    ->where('c.code','IND') 
                    ->where('d.code','IND') 
                    ->where('a.is_deleted','0') 
                    ->selectRaw('a.id, e.description as class_id, a.interest, c.description as category, a.created_at, a.created_by, d.asset_name, a.owner_name, CASE WHEN a.active = 1 THEN "ACTIVE" WHEN a.active = 0 THEN "INACTIVE" WHEN a.active = 2 THEN "TAKE OUT" WHEN a.active = 3 THEN "CLOSED" END as status, (select get_sisa_investasi_asset(a.id)) as sisa_investasi, (select get_sisa_tenor_asset(a.id)) as sisa_tenor ') 
                    ->orderBy('a.id','desc') 
                    ->get(); 

        return DataTables::of($model) 
                ->addColumn('action', function($model){ 
                    return view('master.asset.action', [ 'model' => $model, 'url_show'=> route('asset.show', base64_encode($model->id) ), 'url_edit'=> route('asset.edit', base64_encode($model->id) ), 'url_destroy'=> route('asset.destroy', base64_encode($model->id) ) ]); 
                }) 
                ->editColumn('status', function($model){ 
                    // $label = ''; 
                    // if($model->status == 'ACTIVE') 
                    //     $label = '<span class="label label-success">ACTIVE</span>'; 
                    // elseif($model->status == 'INACTIVE') 
                    //     $label = '<span class="label label-default">INACTIVE</span>'; 
                    // elseif($model->status == 'TAKE OUT') 
                    //     $label = '<span class="label label-danger">TAKE OUT</span>'; 
                    // elseif($model->status == 'CLOSED') 
                    //     $label = '<span class="label label-warning">CLOSED</span>'; 
                    // return $label; 
                    return $model->status;
                }) 
                ->addColumn('status_investasi', function($model){ 
                    $label = ''; 
                    if ($model->sisa_tenor < 1 || $model->sisa_investasi < 100000) { 
                        $label = 'CLOSED'; } 
                    else { $label = 'OPEN'; } 
                    return $label; 
                }) 
                ->editColumn('interest', function($model){ return $model->interest.' %'; }) 
                ->editColumn('sisa_investasi', function($model){ return number_format($model->sisa_investasi,0,'.','.').' IDR'; }) 
                ->editColumn('sisa_tenor', function($model){ return $model->sisa_tenor < 0 ? '0 Days' : $model->sisa_tenor.' Days'; }) 
                ->editColumn('created_at', function($model){ return date('d-m-Y H:i:s', strtotime($model->created_at)); }) 
                ->editColumn('created_by', function($model){ $uti = new utility(); return $uti->getUser($model->created_by); }) 
        // 
                ->addIndexColumn() ->rawColumns(['action', 'created_at', 'created_by', 'status', 'status_investasi', 'interest', 'sisa_investasi', 'sisa_tenor']) ->make(true); 
    }

    public function dataInvestor($id)
    {
        $model = DB::select("
            SELECT 
                i.full_name AS investor,
                i.is_dummy,
                ai.amount,
                ai.invest_tenor AS tenor,
                ai.number_interest AS interest,
                (SELECT 
                        F_SUM_INTEREST(ai.amount,
                                    ai.number_interest,
                                    ai.invest_tenor,
                                    8)
                    ) AS daily_interest,
                (SELECT GET_TOTAL_INTEREST_GIVEN_PER_ASSET(ai.id)) AS total_interest,
                ai.status,
                case
                    when ai.status = 'ACTIVE' then
                    case
                        when current_date() < ai.date_start then
                            (ai.invest_tenor - datediff(current_date(), ai.date_start) -1)
                        else
                            (ai.invest_tenor - datediff(current_date(), ai.date_start))
                    end
                    when ai.status = 'CLOSED' then
                        0
                    when ai.status = 'CANCELED' then
                        (ai.invest_tenor - datediff(ai.canceled_at, ai.date_start))
                end sisa_tenor
            FROM
                trc_asset_investor AS ai
                    LEFT JOIN
                trc_transaction_asset AS ta ON ai.trc_asset_id = ta.id
                    LEFT JOIN
                mst_investor AS i ON i.id = ta.investor_id
            WHERE
                ai.asset_id = ".$id."
            ORDER BY ai.date_start;
        ");

        return DataTables::of($model)
            ->editColumn('daily_interest', function($model){
                return "Rp " . number_format($model->daily_interest,0,',','.');
            })
            ->editColumn('total_interest', function($model){
                return "Rp " . number_format($model->total_interest,0,',','.');
            })
            ->editColumn('investor', function($model){
                return $model->is_dummy == 1 ? $model->investor.' <span class="label bg-gray">DUMMY</span>' : $model->investor;
            })
            ->editColumn('amount', function($model){
                return "Rp " . number_format($model->amount,0,',','.');
            })
            ->editColumn('status', function($model){
                $label = '';
                if($model->status == 'ACTIVE')
                    $label = '<span class="label label-success">Active</span>';
                elseif($model->status == 'CLOSED')
                    $label = '<span class="label label-primary">Closed</span>';
                elseif($model->status == 'CANCELED')
                    $label = '<span class="label label-danger">Canceled</span>';

                return $label;
            })
            ->editColumn('tenor', function($model){
                return $model->tenor.' <span class="label label-danger">-'.$model->sisa_tenor.'</span>';
            })
            ->rawColumns(['daily_interest', 'total_interest', 'amount', 'status', 'tenor', 'investor'])
            ->make(true);
    }

    public function publish(Request $request) {
        $id = base64_decode($request->id);

        $asset = DB::table('mst_asset')->where([
            ['id', '=', $id],
            ['active', '=', 0]
        ])->count();

        if ($asset == 1) {
            DB::table('mst_asset')
              ->where('id', $id)
              ->update(['active' => 1]);

            return 1;
        } else {
            return 0;
        }
    }

    public function takeOut(Request $request) {
        $id = base64_decode($request->id);

        $asset = DB::table('mst_asset')->where([
            ['id', '=', $id],
            ['active', '=', 1]
        ])->count();

        if ($asset == 1) {
            $now = date('Y-m-d H:i:s');

            
                DB::table('mst_asset')
                  ->where('id', $id)
                  ->update([
                    'active' => 2,
                    'canceled_at' => $now,
                    'canceled_by' => Auth::id(),
                    'canceled_reason' => $request->alasanTO
                ]);

                DB::table('trc_asset_investor')->where([
                    ['asset_id', '=', $id],
                    ['status', '=', 'ACTIVE']
                ])->update([
                    'status' => 'CANCELED',
                    'canceled_at' => $now
                ]);


                $trans = DB::select("select a.id, b.investor_id, a.amount, a.transaction_number_detail, c.email, c.full_name, d.asset_name ind_asset_name, e.asset_name eng_asset_name

                from trc_transaction_detail_asset a

                join trc_transaction_asset b on a.trc_asset_id = b.id

                join mst_investor c on b.investor_id = c.id

                join mst_asset_lang d on a.asset_id = d.asset_id and d.code = 'IND'

                join mst_asset_lang e on a.asset_id = e.asset_id and e.code = 'ENG'

                where a.asset_id = ? and a.active = 1 and a.is_deleted = 0 and b.status = 'PAID'", [$id]);

                foreach ($trans as $row) {
                    DB::update("update trc_transaction_detail_asset set active = 2, canceled_at = ? where id = ?", [$now, $row->id]);

                    $transaction_balance_in_id = DB::table('trc_transaction_balance_in')->insertGetId([
                        'investor_id' => $row->investor_id,
                        'transaction_number' => str_replace('TRS/03', 'TRS/00', $row->transaction_number_detail),
                        'date' => $now,
                        'amount' => $row->amount,
                        'information' => 'Cancel Investment',
                        'status' => 'VERIFIED'
                    ]);

                    $balance = DB::table('trc_balance')->select('balance', 'investment')->where('investor_id', $row->investor_id)->orderBy('id', 'desc')->take(1)->first();

                    DB::table('trc_balance')->insert([
                        'investor_id' => $row->investor_id,
                        'transaction_balance_in_id' => $transaction_balance_in_id,
                        'transaction_number' => str_replace('TRS/03', 'TRS/00', $row->transaction_number_detail),
                        'date' => $now,
                        'credit' => $row->amount,
                        'balance' => $balance->balance + $row->amount,
                        'investment' => $balance->investment - $row->amount,
                        'information' => 'Cancel Investment',
                        'active' => 1
                    ]);

                    $msg = 'Halo '.$row->full_name.',<br><br>

                            Investasi Anda pada '.$row->ind_asset_name.' sebesar '.number_format($row->amount).' IDR telah dibatalkan otomatis dikarenakan pihak debitur telah melunasi Asset yang Anda investasikan. Silahkan pilih Asset lain untuk kembali berinvestasi.<br><br>

                            Catatan :<br>Dana yang di investasikan akan kembali ke saldo Kop-Aja Anda.<br><br>

                            Salam,<br><br>

Kop-Aja<br><br><br><br>

                            Hello '. $row->full_name.',<br><br>

                            Your investment in '. $row->eng_asset_name.' for '.number_format($row->amount).' IDR has been canceled automatically because the debtor has paid off the Asset that you invested. Please choose another Asset to re-invest. <br> <br>

                            Note:<br>The invested funds will return to your Kop-Aja balance.<br><br>

Regards,<br><br>

Kop-Aja
                            ';



                    DB::statement('CALL add_email_queue(?, ?, ?, ?, ?, ?, ?, ?, ?, @vret)', [$row->email, $row->full_name, 'Pemberitahuan Pengembalian Dana Investasi', $msg, NULL, NULL, NULL, 'ADMIN', 0]);
                }


            return 1;
        } else {
            return 0;
        }
    }

    public function countData()
    {
        $model = new MstAsset;
        $statusActive = $model->countAssetByStatus(1);
        $statusTakeout = $model->countAssetByStatus(2);
        $statusClosed = $model->countAssetByStatus(3);
        $investasiOpen = $model->countAssetByStatusInvestasi(2);
        $investasiClosed = $model->countAssetByStatusInvestasi(1);

        echo json_encode([
            'statusActive' => $statusActive,
            'statusTakeout' => $statusTakeout,
            'statusClosed' => $statusClosed,
            'investasiOpen' => $investasiOpen,
            'investasiClosed' => $investasiClosed,
        ]);
    }

}
