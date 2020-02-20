<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecAccessLevel;
use App\Models\SecModule;
use App\Models\SecRole;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use PDO;
use App\Utility;


class SecAccessLevelController extends Controller
{
    const MODULE_NAME = 'Access Role';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('setting.access-level.index');
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

        $model = new SecAccessLevel();
        $page = SecModule::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('module', 'id')
                    ->all();
		$page1 = SecRole::where('active','1')
					->where('is_deleted','0')
					->pluck('role', 'id')
					->all();

        return view('setting.sec_access_level.create', compact(['model','page','page1']));
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
            'module_id'  => 'required',
            'role_id'  => 'required'
        ]);

        $userId = Auth::user()->id;
        $create = $request->get('create') ? 1 : 0;
        $read = $request->get('read') ? 1 : 0;
        $update = $request->get('update') ? 1 : 0;
        $delete = $request->get('delete') ? 1 : 0;
        $view = $request->get('view') ? 1 : 0;

        $data = [
            'module_id'          => $request->module_id,
            'role_id'       	 => $request->role_id,
            'access_create'      => $create,
            'access_read'      => $read,
            'access_update'      => $update,
            'access_deleted'      => $delete,
            'access_view'      => $view,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = SecAccessLevel::create($data);
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
        $model = new SecAccessLevel;
        
        $role = SecRole::findOrFail($id);

        $modules = SecModule::where('active','1')
                    ->where('is_deleted','0')
                    ->where('parent_id',null)
                    ->orderBy('sort')
                    ->get();

        $actions = $model->getCheckedActions($id);
        
        return view('setting.access-level.detail', compact(['role','modules','model','actions']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = SecAccessLevel::findOrFail($id);
        $page = SecModule::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('module', 'id')
                    ->all();
		$page1 = SecRole::where('active','1')
					->where('is_deleted','0')
					->pluck('role', 'id')
					->all();
        return view('setting.sec_access_level.create', compact(['model','page','page1']));
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
            'module_id'  => 'required',
            'role_id'  => 'required'
        ]);

        $userId = Auth::user()->id;
        $create = $request->get('create') ? 1 : 0;
        $read = $request->get('read') ? 1 : 0;
        $update = $request->get('update') ? 1 : 0;
        $delete = $request->get('delete') ? 1 : 0;
        $view = $request->get('view') ? 1 : 0;
		
        $data = [
            'module_id'          => $request->module_id,
            'role_id'       	 => $request->role_id,
            'access_create'      => $create,
            'access_read'      => $read,
            'access_update'      => $update,
            'access_deleted'      => $delete,
            'access_view'      => $view,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = SecAccessLevel::findOrFail($id);
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

        DB::update("update sec_accesslevel set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }
	
    public function dataTable()
    {
        $model = SecRole::query();
        $model->where('is_deleted','0');
        $model->where('active','1');
        $model->orderBy('id','desc');
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('setting.access-level.action', [
                    'model' => $model,
                    'url_show'=> route('access-level.show', $model->id)
                ]);
            })              
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addAct(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'role'  => 'required',
            'module'  => 'required',
            'action'=> 'required'
        ]);

        if($validate)
        {
            $role = $request->get('role');
            $module = $request->get('module');
            $action = $request->get('action');

            $data = [
                'role_id' => $role,
                'module_id' => $module,
                'action' => $action,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $model = SecAccessLevel::create($data);
            \UserLogActivity::addLog('Add Role #'.$model->role_id.' Module #'.$model->module_id.' Action '.$model->action.' Successfully');
            return $model;
        }
    }

    public function removeAct(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'role'  => 'required',
            'module'  => 'required',
            'action'=> 'required'
        ]);

        if($validate)
        {
            $role = $request->get('role');
            $module = $request->get('module');
            $action = $request->get('action');

            $model = DB::table('sec_accesslevel')
                    ->where('module_id', $module)
                    ->where('role_id', $role)
                    ->where('action', $action)
                    ->delete();

             \UserLogActivity::addLog('Remove Role #'.$role.' Module #'.$module.' Action '.$action.' Successfully');
            return $model;
        }
    }
}
