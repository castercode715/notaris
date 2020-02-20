<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MDocumentsModel;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MDocumentsControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.document.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MDocumentsModel();
        return view('master.document.create', compact('model'));
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
            'name'          => 'required|string|unique:m_documents,name,1,is_deleted',
            'sort'          => 'required|numeric|unique:m_documents,sort,1,is_deleted'
        ]);

        $userId = Auth::user()->id;

        $data = [
            'name'      => $request->name,
            'sort'    => $request->sort,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];
        
        MDocumentsModel::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = MDocumentsModel::findOrFail($id);
        $uti = new Utility();
        return view('master.document.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MDocumentsModel::findOrFail($id);
        return view('master.document.create', compact('model'));
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
            'name'          => 'required|string|unique:m_documents,name,'.$id.',id,is_deleted,0',
            'sort'          => 'required|numeric|unique:m_documents,sort,'.$id.',id,is_deleted,0'
        ]);

        $model = MDocumentsModel::findOrFail($id);
        $userId = Auth::user()->id;
        
        $data = [
            'name'          => $request->name,
            'sort'          => $request->sort,
            'updated_by'    => $userId
        ];  
            
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

        DB::update("update m_documents set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable()
    {
        $model = MDocumentsModel::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.document.action', [
                    'model' => $model,
                    'url_show'=> route('document.show', $model->id),
                    'url_edit'=> route('document.edit', $model->id),
                    'url_destroy'=> route('document.destroy', $model->id)
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
}
