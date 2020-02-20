<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstAssetCategory;
use App\Models\MstAssetCategoryLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstAssetCategoryController extends Controller
{
    const MODULE_NAME = 'Category';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.asset_category.index');
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

        $model = new MstAssetCategoryLang;

        $language = MstLanguage::all();

        return view('master.asset_category.create', compact(['model','language']));
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
            'desc'      => 'required|string|unique:mst_asset_category,desc,1,is_deleted',
        ]);*/
        $validate = $this->validate($request, [
            'code.*'  => 'required|distinct|min:1',
            'description.*'  => 'required',
            'active'    => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            
            $data = [
                'active'        => $active,
                'created_by'    => $userId,
                'updated_by'    => $userId
            ];

            if($model = MstAssetCategory::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    $data = [
                        'asset_category_id' => $model->id,
                        'code'          => $request->code[$key],
                        'description'   => $request->description[$key]
                    ];
                    MstAssetCategoryLang::create($data);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/asset-category/'. base64_encode($model->id) )->with('success','Success');
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
        $model = MstAssetCategory::findOrFail($id);
        $data = DB::table('mst_asset_category_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.asset_category_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        $uti = new Utility();
        return view('master.asset_category.detail', compact(['model', 'data', 'uti']));
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
        
        $model = MstAssetCategory::findOrFail($id);

        $data = DB::table('mst_asset_category_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.asset_category_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        return view('master.asset_category.edit', compact(['model','data']));
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
        /*$this->validate($request, [
            'desc'      => 'required|string|unique:mst_asset_category,desc,'.$id.',id,is_deleted,0',
        ]);*/
        $validate = $this->validate($request, [
            'code.*'  => 'required|distinct|min:1',
            'description.*'  => 'required',
            'active'    => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstAssetCategory::findOrFail($id);
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            
            $data = [
                'active'        => $active,
                'created_by'    => $userId,
                'updated_by'    => $userId
            ];

            if($model->update($data))
            {
                foreach($request->id as $key => $value)
                {
                    $lang = MstAssetCategoryLang::findOrFail($value);

                    $data = [
                        'code'          => $request->code[$key],
                        'description'   => $request->description[$key]
                    ];

                    $lang->update($data);
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/asset-category/'. base64_encode($model->id) )->with('success','Success');
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
        $deleted_at   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_asset_category set 
            deleted_at='".$deleted_at."',
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

        $delete = DB::update("update mst_asset_category set 
            deleted_at='".$deleted_at."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/asset-category')->with('success', 'Deleted');
        }
        else
            return redirect('master/asset-category/'.base64_encode($id))->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::table('mst_asset_category as a')
                    ->join('mst_asset_category_lang as b','a.id','=','b.asset_category_id')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','a.created_at','a.created_by','b.description')
                    ->orderBy('a.id','desc')
                    ->get();
                        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.asset_category.action', [
                    'model' => $model,
                    'url_show'=> route('asset-category.show', base64_encode($model->id) ),
                    'url_edit'=> route('asset-category.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('asset-category.destroy', base64_encode($model->id) )
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

        $model = DB::table('mst_asset_category_lang as a')
                    ->join('mst_language as b','a.code','b.code')
                    ->where('a.asset_category_id',$id)
                    ->select('a.description','b.language','a.id')
                    ->orderBy('a.code')
                    ->get();
        return DataTables::of($model)
                ->addIndexColumn()
                ->make(true);
    }
}
