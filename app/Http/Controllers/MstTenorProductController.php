<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstTenorModels; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;


class MstTenorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.tenor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstTenorModels();
        return view('master.tenor.create', compact('model'));
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
            'tenor'          => 'required|string',
            'bunga'          => 'required|string',
        ]);

        $data = [
            'tenor'      => $request->tenor,
            'bunga'      => $request->bunga
        ];

        MstTenorModels::create($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = MstTenorModels::findOrFail($id);
        $uti = new Utility();
        return view('master.tenor.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MstTenorModels::findOrFail($id);
        return view('master.tenor.create', compact('model'));
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
            'tenor'          => 'required|string',
            'bunga'          => 'required|string',
        ]);

        $model = MstTenorModels::findOrFail($id);
        $data = [
            'tenor'      => $request->tenor,
            'bunga'      => $request->bunga
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
        $delete = MstTenorModels::where('id', $id)->delete();
    }

    public function dataTable()
    {
        $model = MstTenorModels::query();
        // $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.tenor.action', [
                    'model' => $model,
                    'url_show'=> route('product-tenor.show', $model->id),
                    'url_edit'=> route('product-tenor.edit', $model->id),
                    'url_destroy'=> route('product-tenor.destroy', $model->id)
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
