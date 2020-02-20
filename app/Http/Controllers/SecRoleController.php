<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecRole;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class SecRoleController extends Controller
{
    const MODULE_NAME = 'Role';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.role.index');
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

        $model = new SecRole();
        return view('master.role.form', compact('model'));
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
            'code'  => 'required|string|max:5|unique:sec_role,code,1,is_deleted',
            'role'  => 'required|string',
            'active'=> 'required'
        ]);

        $id = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $roles = [
            'code'  => strtoupper($request->code),
            'role'  => ucwords($request->role),
            'active'=> $active,
            'created_by'    => $id,
            'updated_by'    => $id
        ];

        $model = SecRole::create($roles);

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

        $model = SecRole::findOrFail($id);
        $uti = new Utility();
        return view('master.role.detail', compact(['model', 'uti']));
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

        $model = SecRole::findOrFail($id);
        return view('master.role.form', compact('model'));
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
            'code'  => 'required|string|max:5|unique:sec_role,code,'. $id.',id,is_deleted,0',
            'role'  => 'required|string',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $roles = [
            'code'      => strtoupper($request->code),
            'role'      => ucwords($request->role),
            'active'    => $active,
            'updated_by'=> $userId
        ];

        $model = SecRole::findOrFail($id);
        $model->update($roles);
        \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
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

        // $model = SecRole::findOrFail($id);
        // $model->delete();
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update sec_role set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = SecRole::query();
        $model->where('is_deleted','<>','1');
                
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.role.action', [
                    'model' => $model,
                    'url_show'=> route('role.show', $model->id),
                    'url_edit'=> route('role.edit', $model->id),
                    'url_destroy'=> route('role.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            // ->editColumn('active', function($model){
            //     return $model->active == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive<span>';
            // })
            ->addIndexColumn()
            ->rawColumns(['action', 'checkbox', 'create_at', 'created_by'])
            ->make(true);
    }
}
