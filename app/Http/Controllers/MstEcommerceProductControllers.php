<?php

namespace App\Http\Controllers;

use App\Models\MstProductEcommerce; 
use App\Models\MstAttributeEcommerce; 
use App\Models\MstProductImg; 
use App\Models\MstTenorProductModels; 
use App\Models\MstProductAttribute; 
use App\Models\MstProductCategory; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstEcommerceProductControllers extends Controller
{
    protected $hasilCat = array();

    public function membersTree($id = 0)
    {
        $classes_str = '';
        $sql = DB::table('mst_category_ecommerce as a')
                ->where('a.is_deleted', 0)
                ->where('a.parent_id', $id)
                ->get();

        $result = [];

        foreach ($sql as $key) {

            $a = $this->membersTree($key->id);

            if(empty($a)){
                $result[] = [
                    'id' => $key->id,
                    'title' => $key->name,
                ];
            }else{
                $result[] = [
                    'id' => $key->id,
                    'title' => $key->name,
                    'subs' => $this->membersTree($key->id)
                ];
            }
            
            
        }

       // dd($classes_str);

        return $result;
    }

    public function getChild()
    {
        $this->hasilCat = $this->membersTree();
        return json_encode($this->hasilCat);
        // dd(json_encode($this->hasilCat));
    }

    /* ---------------------------------------
        INDEX
    -----------------------------------------*/
    public function index()
    {
        
        $this->membersTree();
        $model = new MstProductEcommerce;
        return view('master.ecommerce.index', compact('model'));
        // return json_encode($this->hasilCat);
    }

    /* ---------------------------------------
        CREATE
    -----------------------------------------*/
    public function create()
    {
        $model = new MstProductEcommerce();
        $attr = DB::table('mst_attribute_ecommerce as a')
                    ->where('is_deleted','0')
                    ->where('active','1')
                    ->get();

        $attribute = DB::table('mst_attribute_ecommerce as a')
                    ->where('is_deleted','0')
                    ->where('active','1')
                    ->pluck('a.name','a.id')
                    ->all();

        $category = DB::table('mst_category_ecommerce as a')
                    ->where('a.is_deleted', '0')
                    ->pluck('a.name','a.id')
                    ->all();

        $tenor = DB::table('mst_tenor as a')
                    ->pluck('a.tenor','a.id')
                    ->all();

        $categorys = DB::table('mst_category_ecommerce as a')
                ->where('a.is_deleted', 0)
                ->where('a.parent_id', 0)
                ->get();

        return view('master.ecommerce.create', compact('model','attr','attribute','category','tenor','categorys'));
    }

    /* ---------------------------------------
        STORED DATA
    -----------------------------------------*/
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|unique:mst_product_ecommerce,name,1,is_deleted',
            'price'             => 'required',
            'tenor_id.*'        => 'required',
            'discount'          => 'required',
            'status'            => 'required',
            'desc'              => 'required',
            'term_conds'        => 'required',
            'attr_name.*'       => 'string',
            'attr_value.*'      => 'string',
            'values.*'          => 'string',
        ]);

        $category_val = explode(",",$request->values);

        $attribute = $request->attr_name;
        $valueat = $request->attr_value;
        $userId = Auth::user()->id;

        $x = count($attribute);
        $y = $x--;

        $dataProduct = [
            'name' => $request->name,
            'price' => str_replace(',', '', $request->price),
            'discount' => $request->discount,
            'status' => $request->status,
            'desc' => $request->desc,
            'term_conds' => $request->term_conds,
            'created_by' => $userId,
            'updated_by' => $userId,
        ];

        $model = MstProductEcommerce::create($dataProduct);

        if ($model) {

            /* ---------------------------------------
                Tenor Product
            -----------------------------------------*/
            $tenor = $request->tenor;
            if($tenor != null){

                foreach ($tenor as $value) {
                    $tenorData = [
                        'tenor_id' => $value,
                        'product_id' => $model->id,
                    ];

                    MstTenorProductModels::create($tenorData);
                }

            }

            /* ---------------------------------------
                Product Category
            -----------------------------------------*/
            $categorys = $request->category;
            if($category_val != null){

                foreach ($category_val as $value) {
                    
                    $dataCategory = [
                        'product_id'    => $model->id,
                        'category_id'   => $value,
                    ];

                    MstProductCategory::create($dataCategory);
                }
                
                
            }

            /* ---------------------------------------
                FEATURED
            -----------------------------------------*/
            $featured = $request->featured;
            if($featured != null){

                $arrayData = [
                    'product_id'    => $model->id,
                    'image'         => $featured,
                    'featured'      => 'Y',
                ];

                MstProductImg::create($arrayData);
            }

            /* ---------------------------------------
                ANOTHER 
            -----------------------------------------*/
            $images = $request->image;
            if($images != null){
                foreach ($images as $key) {
                    # code...
                    $AttrImages = [
                        'product_id'    => $model->id,
                        'image'         => $key,
                        'featured'      => 'N',
                    ];

                    MstProductImg::create($AttrImages);
                }
            }

            /* ---------------------------------------
                LOOPING ATTRIBUTE 
            -----------------------------------------*/

            $x = count($attribute);
            $y = $x--;


            for ($i=0; $i < $y; $i++) { 
                # code...

                $dataAttr = [
                    'product_id' => $model->id,
                    'attribute_ecommerce_id' => $attribute[$i],
                    'value' => $valueat[$i],
                ];
                
                MstProductAttribute::create($dataAttr);

                
            }

            return redirect('ecommerce/product/'.base64_encode($model->id) )->with('success','Product Successfully Created');

        } else{
            Redirect::back()->withErrors(['error', 'Failed']);
        }
    }

    /* ---------------------------------------
        SHOW 
    -----------------------------------------*/
    public function show($id)
    {
        $id = base64_decode($id);
        $uti = new Utility();
        $model = MstProductEcommerce::findOrFail($id);

        if($model->discount != 0){

            $diskon = ($model->price * $model->discount) / 100;
            $total_price = $model->price - $diskon;
        }else{
            $total_price = $model->price;
        }

        $featuredImg = DB::table('mst_product_img as a')
                    ->where('a.product_id',$id)
                    ->where('a.featured','Y')
                    ->get();

        $otherImg = DB::table('mst_product_img as a')
                    ->where('a.product_id',$id)
                    ->where('a.featured','<>','Y')
                    ->get();

        $attribute = DB::table('mst_product_attribute as a')
                    ->join('mst_attribute_ecommerce as b','b.id','=','a.attribute_ecommerce_id')
                    ->select('a.attribute_ecommerce_id','b.name','a.value')
                    ->where('a.product_id',$id)
                    ->get();

        $attributeValue = DB::table('mst_product_attribute as a')
                    ->join('mst_attribute_ecommerce as b','b.id','=','a.attribute_ecommerce_id')
                    ->select(DB::raw('count(*) as same_data, a.attribute_ecommerce_id'), 'b.name', 'b.id')
                    ->groupBy('a.attribute_ecommerce_id')
                    ->where('a.product_id', $id)
                    ->get();

        $category = DB::table('mst_product_category as a')
                    ->join('mst_category_ecommerce as b', 'b.id','=','a.category_id')
                    ->where('a.product_id', $id)
                    ->select('b.name')
                    ->get();

        $tenor = DB::table('mst_tenor as a')
                    ->join('mst_tenor_product as b','b.tenor_id','=','a.id')
                    ->where('b.product_id', $id)
                    ->get();

                    // dd($attributeValue);

        return view('master.ecommerce.detail', compact(['model','featuredImg','otherImg','uti','attribute','attributeValue','category','tenor','total_price']));
    }

    /* ---------------------------------------
        SHOW TO UPDATE
    -----------------------------------------*/
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = MstProductEcommerce::findOrFail($id);
        $uti = new utility();

        $categorys = DB::table('mst_category_ecommerce as a')
                ->where('a.is_deleted', 0)
                ->where('a.parent_id', 0)
                ->get();

        $featuredImg = DB::table('mst_product_img as a')
                    ->where('a.product_id',$id)
                    ->where('a.featured','Y')
                    ->get();

        $otherImg = DB::table('mst_product_img as a')
                    ->where('a.product_id',$id)
                    ->where('a.featured','<>','Y')
                    ->get();

        $attributeList = DB::table('mst_attribute_ecommerce as a')
                    ->where('is_deleted','0')
                    ->where('active','1')
                    ->select('a.id','a.name')
                    ->get();

        $attributes = DB::table('mst_product_attribute as a')
                    ->where('a.product_id',$id)
                    ->select('a.product_id','a.attribute_ecommerce_id', 'a.value')
                    ->get();

        $categoryList = DB::table('mst_category_ecommerce as a')
                        ->where('a.is_deleted', '0')
                        ->pluck('a.name','a.id')
                        ->all();

        $category = MstProductCategory::where('product_id',$id)
                    ->pluck('category_id')
                    ->toArray();

        $tenorList = DB::table('mst_tenor as a')
                    ->pluck('a.tenor','a.id')
                    ->all();

        $tenor = MstTenorProductModels::where('product_id',$id)
                    ->pluck('tenor_id')
                    ->toArray();

        $countAttr = count($attributes);

        return view('master.ecommerce.update', compact(
            'model',
            'uti',
            'tenorList',
            'tenor',
            'featuredImg',
            'otherImg',
            'attributeList',
            'countAttr',
            'categoryList',
            'category',
            'categorys',
            'attributes'
        ));

    }

    /* ---------------------------------------
        UPDATE 
    -----------------------------------------*/
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|unique:mst_product_ecommerce,name,'.base64_decode($id).',id,is_deleted,0',
            'price'             => 'required',
            'discount'          => 'required',
            'status'            => 'required',
            'desc'              => 'required',
            'term_conds'        => 'required',
            'attr_name.*'       => 'string',
            'attr_value.*'      => 'string',
            'values.*'          => 'string',
        ]);

        $model = MstProductEcommerce::findOrFail(base64_decode($id));
        $category_val = explode(",",$request->values);
        $attribute = $request->attr_name;
        $valueat = $request->attr_value;
        $userId = Auth::user()->id;

        $x = count($attribute);
        $y = $x--;

        $dataProduct = [
            'name' => $request->name,
            'price' => str_replace(',', '', $request->price),
            'discount' => $request->discount,
            'status' => $request->status,
            'desc' => $request->desc,
            'term_conds' => $request->term_conds,
            'updated_by' => $userId,
        ];

        $update = $model->update($dataProduct);

        if($update){

            /* ---------------------------------------
                TENOR
            -----------------------------------------*/
            $cekTenor = MstTenorProductModels::where('product_id', base64_decode($id))
                        ->pluck('tenor_id')
                        ->count();

            if ($cekTenor > 0) {
                $delten = MstTenorProductModels::where('product_id', base64_decode($id))->delete();

                if ($delten) {
                    $tenor = $request->tenor;
                    if($tenor != null){

                        foreach ($tenor as $value) {
                            $tenorData = [
                                'tenor_id' => $value,
                                'product_id' => $model->id,
                            ];

                            MstTenorProductModels::create($tenorData);
                        }

                    }
                }
            }else{
                $tenor = $request->tenor;
                if($tenor != null){

                    foreach ($tenor as $value) {
                        $tenorData = [
                            'tenor_id' => $value,
                            'product_id' => $model->id,
                        ];

                        MstTenorProductModels::create($tenorData);
                    }

                }
            }

            /* ---------------------------------------
                CATEGORY
            -----------------------------------------*/
            $cek = MstProductCategory::where('product_id',base64_decode($id))
                    ->pluck('category_id')
                    ->count();

            if ($cek > 0) {
                $delcat = MstProductCategory::where('product_id',base64_decode($id))->delete();

                if($delcat){

                    if($category_val != null){

                        foreach ($category_val as $value) {
                            
                            $dataCategory = [
                                'product_id'    => $model->id,
                                'category_id'   => $value,
                            ];

                            MstProductCategory::create($dataCategory);
                        }
                        
                        
                    }
                }
            }else{

                if($category_val != null){

                    foreach ($category_val as $value) {
                        
                        $dataCategory = [
                            'product_id'    => $model->id,
                            'category_id'   => $value,
                        ];

                        MstProductCategory::create($dataCategory);
                    }
                    
                    
                }

            }


            /* ---------------------------------------
                FEATURED
            -----------------------------------------*/
            $featured = $request->featured;
            if($featured != null){

                $cek = MstProductImg::where('product_id', $model->id)
                                    ->where('featured', 'Y')
                                    ->count();

                if($cek > 0){

                    $delete = MstProductImg::where('product_id', $model->id)
                                            ->where('featured', 'Y')
                                            ->delete();

                        if($delete){

                            $arrayData = [
                                'product_id'    => $model->id,
                                'image'         => $featured,
                                'featured'      => 'Y',
                            ];

                            MstProductImg::create($arrayData);
                        }

                }else{

                    $arrayData = [
                        'product_id'    => $model->id,
                        'image'         => $featured,
                        'featured'      => 'Y',
                    ];

                    MstProductImg::create($arrayData);

                }

                
            }

            /* ---------------------------------------
                ANOTHER 
            -----------------------------------------*/
            $images = $request->image;
            if($images != null){

                $cek = MstProductImg::where('product_id', $model->id)
                                    ->where('featured', 'N')
                                    ->count();


                    if($cek > 0){
                        $delete = MstProductImg::where('product_id', $model->id)
                                            ->where('featured', 'N')
                                            ->delete();

                            if($delete){

                                // dd($images);
                                foreach ($images as $key) {
                                    # code...
                                    $AttrImages = [
                                        'product_id'    => $model->id,
                                        'image'         => $key,
                                        'featured'      => 'N',
                                    ];

                                    MstProductImg::create($AttrImages);
                                }
                            }
                    }else{

                        foreach ($images as $key) {
                                # code...
                                $AttrImages = [
                                    'product_id'    => $model->id,
                                    'image'         => $key,
                                    'featured'      => 'N',
                                ];

                                MstProductImg::create($AttrImages);
                            }
                    }
                
            }

            /* ---------------------------------------
                LOOPING ATTRIBUTE 
            -----------------------------------------*/

            $cek = DB::table('mst_product_attribute as a')
                        ->where('a.product_id',$model->id)
                        ->count();
            if($cek > 0){
                $deleteFirst = MstProductAttribute::where('product_id',$model->id)->delete();

                if($deleteFirst){

                    for ($i=0; $i < $y; $i++) { 
                        $dataAttr = [
                            'product_id' => $model->id,
                            'attribute_ecommerce_id' => $attribute[$i],
                            'value' => $valueat[$i],
                        ];
                        
                        MstProductAttribute::create($dataAttr);
                    }

                }else{
                    Redirect::back()->withErrors(['error', 'Error Attribute Fetching']);
                }

            }else{
                for ($i=0; $i < $y; $i++) { 
                    $dataAttr = [
                        'product_id' => $model->id,
                        'attribute_ecommerce_id' => $attribute[$i],
                        'value' => $valueat[$i],
                    ];
                    
                    MstProductAttribute::create($dataAttr);
                }
            }
            

            return redirect('ecommerce/product/'.base64_encode($model->id) )->with('success','Product Successfully Created');
        }else{
            Redirect::back()->withErrors(['error', 'Failed']);
        }

    }

    /* ---------------------------------------
        DELETE
    -----------------------------------------*/
    public function destroy($id)
    {
        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_product_ecommerce set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function delete($id)
    {
        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_product_ecommerce set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        if ($delete) {
            return redirect('ecommerce/product')->with('success', 'Deleted');
        }
    }

    public function removeAttrImage(Request $request)
    {
        $attr  = $request->get('attr');
        $id  = $request->get('id');

        if($attr == "featured"){

            $model = DB::table('mst_product_img')
                        ->where('featured','Y')
                        ->where('id',$id)
                        ->delete();

                if($model){
                    echo json_encode(array("status" => TRUE));
                }else{
                    echo json_encode(array("status" => FALSE));
                }

        }else if($attr == "other"){

            $model = DB::table('mst_product_img')
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

    public function dataTable()
    {
        $model = MstProductEcommerce::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.ecommerce.action', [
                    'model' => $model,
                    'url_show'=> route('product.show', base64_encode($model->id)),
                    'url_edit'=> route('product.edit', base64_encode($model->id)),
                    'url_destroy'=> route('product.destroy', base64_encode($model->id))
                ]);
            })

            ->editColumn('created_at', function($model){
                return date('d M Y H:i', strtotime($model->created_at))." WIB";
            })

            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
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

            ->addIndexColumn()
            ->rawColumns([
                'action',
                'active',
                'create_at',
                'status',
                'created_by'
            ])
            ->make(true);
    }

    /* ---------------------------------------
        FILE UPLOAD
    -----------------------------------------*/

    public function featured(Request $request)
    {
        $file = $request->img;
        $filename = "featured_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/ecommerce/featured/';

        $file->move($move_path,$filename);
        return $move_path.$filename;

    }

    public function images(Request $request)
    {
        $file = $request->img;
        $filename = "img_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/ecommerce/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }

    /* ---------------------------------------
        FETCH ATTRIBUTE
    -----------------------------------------*/
    public function fetchAttributes(Request $request)
    {
        // $value  = $request->get('value');

        $data = DB::table('mst_attribute_ecommerce as a')
                    ->where('is_deleted','0')
                    ->where('active','1')
                    ->select('a.id','a.name')
                    ->get();
        
        $return = "<option value=''>- Select -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->id."'>".$row->name."</option>";
        }
        echo $return;
    }

    public function getvalue($id)
    {
        $model = DB::table('mst_tenor')
                ->where('id',$id)
                ->first();

        return response()->json([
            'bunga' => $model->bunga
        ]);
    }

    public function getCategory($id)
    {
        $result = DB::table('mst_product_category as a')
                    ->where('a.product_id', $id)
                    ->select('a.category_id')
                    ->get();

        $var = [];

        foreach ($result as $key) {
            $var[] = [
                $key->category_id
            ];
        }

        return $var;

    }

    /* ---------------------------------------
        DROPZONE
    -----------------------------------------*/

    public function showImageFeatured(Request $request)
    {
        $result = DB::table('mst_product_img as a')
                    ->where('a.product_id', $request->id)
                    ->where('a.featured', 'Y')
                    ->get();

        $imgs = [];

        if($result != null){

            foreach ($result as $key) {

                $img['nama'] = substr($key->image, 25);
                $img['size'] = filesize(public_path() ."/". $key->image);
                $img['url'] = $key->image;
                $imgs[] = $img;

            }
        }

        return response()->json(['imgs' => $imgs]);

    }

    public function showImages(Request $request)
    {
        $result = DB::table('mst_product_img as a')
                    ->where('a.product_id', $request->id_imgs)
                    ->where('a.featured', 'n')
                    ->get();

        $imgs = [];

        if($result != null){

            foreach ($result as $key) {

                $img['nama'] = substr($key->image, 25);
                $img['size'] = filesize(public_path() ."/". $key->image);
                $img['url'] = $key->image;
                $imgs[] = $img;

            }
        }
        
        return response()->json(['imgs' => $imgs]);

    }

   
}
