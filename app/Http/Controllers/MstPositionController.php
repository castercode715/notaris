<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPosition;
use App\Models\MstPage;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;
use Illuminate\Validation\Rule;

class MstPositionController extends Controller
{
    const MODULE_NAME = 'Position';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

		return view('master.position.index');
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

        $model = new MstPosition();

        $page = MstPage::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('description', 'id')
                    ->all();

        return view('master.position.form', compact(['model','page']));
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
            'description'  => 'required|string',
            'position_code'=> 'required|string|unique:mst_position,position_code,1,is_deleted',
            'page_id'=> 'required'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'description'          => $request->description,
            'page_id'       => $request->page_id,
            'position_code'       => $request->position_code,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = MstPosition::create($data);
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
        // $model = MstPosition::findOrFail($id);
        $model = DB::table('mst_position as a')
                    ->join('mst_page as b','a.page_id','=','b.id')
                    ->where('a.id', $id)
                    ->select('a.*','b.description as page')
                    ->first();

        $uti = new Utility();
        return view('master.position.detail', compact(['model','uti']));
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
        $model = MstPosition::findOrFail($id);
        $page = MstPage::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('description', 'id')
                    ->all();
        return view('master.position.form', compact(['model','page']));
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
            'description'  => 'required|string',
            'position_code'=> 'required|string|unique:mst_position,position_code,'.$id.',id,is_deleted,0',
            'page_id'=> 'required'
        ]);
        $model = MstPosition::findOrFail($id);
       /* Validator::make($request, [
            'description' => 'required',
            'page_id' => 'required',
            'position_code' => [
                'required',
                Rule::unique('mst_position')->ignore($model->id),
                // Rule::unique('mst_position')->ignore($model->id)->where(function ($query){
                //     return $query->where('is_deleted', 0);
                // }),
            ],
        ]);*/

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'description'          => $request->description,
            'page_id'       => $request->page_id,
            'position_code'       => $request->position_code,
            'active'        => $active,
            'updated_by'    => $userId
        ];

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

        DB::update("update mst_position set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = MstPosition::query();
        $model->where('is_deleted','<>','1');
        $model = DB::table('mst_position as po')
                ->join('mst_page as pg','po.page_id','pg.id')
                ->where('po.is_deleted',0)
                ->select('po.description','po.position_code','pg.description as page','po.id')
                ->orderBy('po.created_at','desc')
                ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.position.action', [
                    'model' => $model,
                    'url_show'=> route('position.show', base64_encode($model->id)),
                    'url_edit'=> route('position.edit', base64_encode($model->id)),
                    'url_destroy'=> route('position.destroy', base64_encode($model->id))
                ]);
            })
            /*->editColumn('created_at', function($model){
                return date('d-m-Y H:i', strtotime($model->created_at)).' WIB';
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->editColumn('page_id', function($model){
                $page = MstPage::where('id', $model->page_id)->first();
                return $page->name;
            })*/
            // ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
