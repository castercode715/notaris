<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstAssetAttribute;
use App\Models\MstAssetAttributeLang;
use App\Models\MstAssetCategory;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class MstAssetAttributeController extends Controller
{
    const MODULE_NAME = 'Attribute';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.asset_attribute.index');
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

        $model = new MstAssetAttribute();

        $category = MstAssetCategory::join('mst_asset_category_lang','mst_asset_category.id','mst_asset_category_lang.asset_category_id')
                    ->where('mst_asset_category_lang.code','IND')
                    ->pluck('mst_asset_category_lang.description','mst_asset_category.id')
                    ->all();

        $language = MstLanguage::all();

        return view('master.asset_attribute.create', compact(['model','category','language']));
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
        /*$this->validate($request, [
            'name'              => 'required|string',
            'category_asset_id' => 'required'
        ]);*/
        $validate = $this->validate($request, [
            'code.*'  => 'required|distinct|min:1',
            'description.*'  => 'required',
            'category_asset_id'    => 'required',
            'active'    => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            
            $data = [
                'category_asset_id' => $request->category_asset_id,
                'active'        => $active,
                'created_by'    => $userId,
                'updated_by'    => $userId
            ];

            if($model = MstAssetAttribute::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    $data = [
                        'asset_attribute_id' => $model->id,
                        'code'          => $request->code[$key],
                        'description'   => $request->description[$key]
                    ];
                    MstAssetAttributeLang::create($data);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/asset-attribute/'. base64_encode($model->id) )->with('success','Success');
            }
            else
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
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $model = MstAssetAttribute::findOrFail($id);
        $category = DB::table('mst_asset_category_lang')
                    ->where('asset_category_id', $model->category_asset_id)
                    ->where('code','IND')
                    ->first();
        $data = DB::table('mst_asset_attribute_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.asset_attribute_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        $uti = new Utility();
        return view('master.asset_attribute.detail', compact(['model', 'data', 'uti', 'category']));
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
        $model = MstAssetAttribute::findOrFail($id);

        $category = MstAssetCategory::join('mst_asset_category_lang','mst_asset_category.id','mst_asset_category_lang.asset_category_id')
                    ->where('mst_asset_category_lang.code','IND')
                    ->pluck('mst_asset_category_lang.description','mst_asset_category.id')
                    ->all();

        $data = DB::table('mst_asset_attribute_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.asset_attribute_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        return view('master.asset_attribute.edit', compact(['model','category','data']));
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

        $validate = $this->validate($request, [
            'code.*'  => 'required|distinct|min:1',
            'description.*'  => 'required',
            'category_asset_id'    => 'required',
            'active'    => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            $model = MstAssetAttribute::findOrFail($id);
            
            $data = [
                'category_asset_id' => $request->category_asset_id,
                'active'            => $active,
                'updated_by'        => $userId
            ];

            if($model->update($data))
            {
                foreach($request->id as $key => $value)
                {
                    $lang = MstAssetAttributeLang::findOrFail($value);

                    $data = [
                        'code'          => $request->code[$key],
                        'description'   => $request->description[$key]
                    ];

                    $lang->update($data);
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/asset-attribute/'. base64_encode($model->id) )->with('success','Success');
            }
            else
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
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_asset_attribute set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $deleted_at   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_asset_attribute set 
            deleted_at='".$deleted_at."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/asset-attribute')->with('success', 'Deleted');
        }
        else
            return redirect('master/asset-attribute/'.base64_encode($id))->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::table('mst_asset_attribute as a')
                    ->join('mst_asset_attribute_lang as b','a.id','=','b.asset_attribute_id')
                    ->join('mst_asset_category as c','c.id','=','a.category_asset_id')
                    ->join('mst_asset_category_lang as d','c.id','=','d.asset_category_id')
                    ->where('d.code','IND')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','d.description as category','a.created_at','a.created_by','b.description')
                    ->orderBy('a.id','desc')
                    ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.asset_attribute.action', [
                    'model' => $model,
                    'url_show'=> route('asset-attribute.show', base64_encode($model->id)),
                    'url_edit'=> route('asset-attribute.edit', base64_encode($model->id)),
                    'url_destroy'=> route('asset-attribute.destroy', base64_encode($model->id))
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i', strtotime($model->created_at)).' WIB';
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'created_at', 'created_by'])
            ->make(true);
    }

    public function dataTableDetail($id)
    {
        $id = base64_decode($id);

        $model = DB::table('mst_asset_attribute_lang as a')
                    ->join('mst_language as b','a.code','b.code')
                    ->where('a.asset_attribute_id', $id)
                    ->select('a.*','b.language')
                    ->get();
        return DataTables::of($model)
                ->addIndexColumn()
                ->make(true);
    }
}
