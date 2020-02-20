<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstCategoryEcommerce; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstProductCategoryControllers extends Controller
{
    protected $hasilCat = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function membersTree($id = 0)
    {
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
                    'name' => $key->name,
                ];
            }else{
                $result[] = [
                    'id' => $key->id,
                    'name' => $key->name,
                    'child' => $this->membersTree($key->id)
                ];
            }
            
        }

        return $result;
    }

    // protected function getCategory($id = 0)
    // {
        

    //     $sql = DB::table('mst_category_ecommerce as a')
    //             ->where('a.is_deleted', 0)
    //             ->where('a.parent_id', $id)
    //             ->get();

    //     // dd($sql);

    //     if($sql->isEmpty() == false){

    //         foreach ($sql as $key) {
    //             $child = $this->membersTree($key->id);

    //             $this->hasilCat[] = [
    //                 'id' => $key->id,
    //                 'name' =>$key->name,
    //                 'child' => $child
    //             ];
    //         }
    //     }

    // }

    public function index()
    {
        // $this->hasilCat = $this->membersTree();

        // $parent_key = 0;
        // $sql = DB::table('mst_category_ecommerce as a')
        //         ->where('a.is_deleted', 0)
        //         ->where('a.parent_id', '<>', 3)
        //         ->get();

        // foreach ($sql as $key) {
        //     $data = [
        //         'id' => $key->id,
        //         'name' => $key->name,
        //         'child' => Self::membersTree($parent_key)
        //     ];
        // }


        // return response()->json(['data' => $this->hasilCat]);

        $model = new MstCategoryEcommerce();
        $categories = DB::table('mst_category_ecommerce as a')
            ->where('a.parent_id', 0)
            ->where('a.is_deleted', 0)
            ->orderBy('a.id', 'ASC')
            ->get();

        $allCategory = $model->where('is_deleted', '<>', 1)->orderBy('is_deleted', 'desc')->pluck('name', 'id')->all();
        
        return view('master.product-category.index', compact('categories','model','allCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstCategoryEcommerce();
        
        return view('master.product-category.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'          => 'required|string|unique:mst_category_ecommerce,name,1,is_deleted',
            'parent_id'     => 'required|string',
        ]);

        $model = new MstCategoryEcommerce();
        $userId = Auth::user()->id;

        if($model->isAllowedParent($request->parent_id) > 0){

            $data = [
                'name'       => $request->name,
                'image'      => $request->icon_category,
                'parent_id'  => 0,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

        }else{

            $data = [
                'name'       => $request->name,
                'image'      => $request->icon_category,
                'parent_id'  => $request->parent_id,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

        }

        $create = MstCategoryEcommerce::create($data);

        if($create){
            return redirect('ecommerce/product-category/')->with('success','Category successfully created');
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
        $model = MstCategoryEcommerce::findOrFail($id);
        $uti = new Utility();
        return view('master.product-category.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MstCategoryEcommerce::findOrFail($id);
        $allCategory = $model->where('is_deleted', '<>', 1)->orderBy('is_deleted', 'desc')->pluck('name', 'id')->all();
        return view('master.product-category.create', compact('model','allCategory'));
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
        $this->validate($request,[
            'name'          => 'required|string|unique:mst_category_ecommerce,name,'.$id.',id,is_deleted,0',
            'parent_id'     => 'required|string',
        ]);

        $model = MstCategoryEcommerce::findOrFail($id);
        $userId = Auth::user()->id;

        if($model->isAllowedParent($request->parent_id) > 0){

            if($request->icon_category2 != null){
                $data = [
                    'name'       => $request->name,
                    'image'      => $request->icon_category2,
                    'parent_id'  => 0,
                    'updated_by' => $userId
                ];
            }else{
                $data = [
                    'name'       => $request->name,
                    'parent_id'  => 0,
                    'updated_by' => $userId
                ];
            }

            

        }else{

            if($request->icon_category2 != null){
                $data = [
                    'name'       => $request->name,
                    'image'      => $request->icon_category2,
                    'parent_id'  => $request->parent_id,
                    'updated_by' => $userId
                ];
            }else{
                $data = [
                    'name'       => $request->name,
                    'parent_id'  => $request->parent_id,
                    'updated_by' => $userId
                ];
            }

        }
            
        $model->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;
        $model = new MstCategoryEcommerce();

        if($model->isAllowedDelete($id) > 0)
            abort(400, 'Table has related.');

        DB::update("update mst_category_ecommerce set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable()
    {
        $model = MstCategoryEcommerce::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.product-category.action', [
                    'model' => $model,
                    'url_show'=> route('product-category.show', $model->id),
                    'url_edit'=> route('product-category.edit', $model->id),
                    'url_destroy'=> route('product-category.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }

    public function iconCategory(Request $request)
    {
        $file = $request->img;
        $filename = "pc_".date('Ymd_His')."_".$file->getClientOriginalName();
        $move_path = 'images/category-ecommerce/';

        $file->move($move_path,$filename);
        return $move_path.$filename;
    }
}
