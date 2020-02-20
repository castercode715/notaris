<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecModule;
use App\Models\MstEmployee;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class SecModuleController extends Controller
{
    const MODULE_NAME = 'Menu';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('setting.menu.index');
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

        $model = new SecModule();
        $page = SecModule::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('module', 'id')
                    ->all();

        return view('setting.menu.create', compact(['model','page']));
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

        $this->validate($request, [
            'module'  => 'required|string',
            // 'link'  => 'required',
            // 'icon'  => 'required'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'module'          => $request->module,
            'link'       => $request->link,
            'icon'       => $request->icon,
            'sort'       => $request->sort,
            'parent_id'       => $request->parent_id,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = SecModule::create($data);
        \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
        return $model;
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

		// $model = DB::table('sec_module as a')
  //                   ->join('sec_module as b','a.parent_id','=','b.id')
		//             ->where('a.id',$id)
  //                   ->select('a.*','b.modu	le as nm','a.module','b.parent_id as nm_baru')
  //                   ->first();
		$model = SecModule::findOrFail($id);
        $uti = new Utility();
        return view('setting.menu.detail', compact(['model','uti']));
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

        $model = SecModule::findOrFail($id);
        $page = SecModule::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('module', 'id')
                    ->all();
        return view('setting.menu.create', compact(['model','page']));
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

        $this->validate($request, [
            'module'  => 'required|string',
            // 'link'  => 'required',
            // 'icon'  => 'required'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'module'          => $request->module,
            'link'       => $request->link,
            'icon'       => $request->icon,
            'sort'       => $request->sort,
            'parent_id'       => $request->parent_id,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = SecModule::findOrFail($id);
        \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
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
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $deleted_date   = date('Y-m-d H:i:s');
		$deleted_by     = Auth::user()->id;

        DB::update("update sec_module set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }
	
	public function dataTable()
    {
        $model = SecModule::query();
        $model->where('is_deleted','<>','1');
		
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('setting.menu.action', [
                    'model' => $model,
                    'url_show'=> route('menu.show', $model->id),
                    'url_edit'=> route('menu.edit', $model->id),
                    'url_destroy'=> route('menu.destroy', $model->id)
                ]);
            })
            ->editColumn('parent_id', function($model){
                $result = null;
                if($model->parent_id != null){
                    $parent = SecModule::find($model->parent_id);
                    $result = $parent->module;
                }
                return $result;
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'created_at', 'created_by', 'parent_id'])
            ->make(true);
    }
}
