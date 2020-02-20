<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPage;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstPageController extends Controller
{
    const MODULE_NAME = 'Page';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.page.index');
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

        $model = new MstPage();
        return view('master.page.form', compact('model'));
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
            'description'  => 'required|string|unique:mst_page,description,1,is_deleted',
        ]);

        $userId = Auth::user()->id;

        $data = [
            'description'   => $request->description,
            'active'        => $request->active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = MstPage::create($data);
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

        $id = base64_decode($id);
        $model = MstPage::findOrFail($id);
        $uti = new Utility();
        return view('master.page.detail', compact(['model','uti']));
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
        $model = MstPage::findOrFail($id);
        return view('master.page.form', compact('model'));
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
            'description'  => 'required|string|unique:mst_page,description,'.$id.',id,is_deleted,0',
            // 'link'  => 'required|string|unique:mst_page,link,'.$id.',id,is_deleted,0',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'description'          => $request->description,
            // 'link'          => $request->link,
            'active'        => $active,
            'updated_by'    => $userId
        ];

        $model = MstPage::findOrFail($id);
        $model->update($data);
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
        $id = base64_decode($id);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_page set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = MstPage::query();
        $model->where('is_deleted','<>','1');

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.page.action', [
                    'model' => $model,
                    'url_show'=> route('page.show', base64_encode($model->id) ),
                    'url_edit'=> route('page.edit', base64_encode($model->id)),
                    'url_destroy'=> route('page.destroy', base64_encode($model->id))
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
}
