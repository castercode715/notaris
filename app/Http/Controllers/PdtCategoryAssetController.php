<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdtCategoryAsset;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class PdtCategoryAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.asset_category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new PdtCategoryAsset();
        return view('master.asset_category.form', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'desc'      => 'required|string|unique:pdt_category_asset,desc,1,is_deleted',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'desc'          => $request->desc,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = PdtCategoryAsset::create($data);
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
        $model = PdtCategoryAsset::findOrFail($id);
        $uti = new Utility();
        return view('master.asset_category.detail', compact(['model', 'uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = PdtCategoryAsset::findOrFail($id);
        return view('master.asset_category.form', compact('model'));
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
            'desc'      => 'required|string|unique:pdt_category_asset,desc,'.$id.',id,is_deleted,0',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'desc'          => $request->desc,
            'active'        => $active,
            'updated_by'    => $userId
        ];

        $model = PdtCategoryAsset::findOrFail($id);
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
        // $model = PdtCategoryAsset::findOrFail($id);

        // $data = [
        //     'deleted_date'  => date('Y-m-d H:i:s'),
        //     'deleted_by'    => Auth::user()->sec_employee_id,
        //     'is_deleted'    => '1'
        // ];
        
        // $model->update($data);
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update pdt_category_asset set 
            deleted_date='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable()
    {
        // $model = PdtCategoryAsset::query();
        $model = DB::table('pdt_category_asset')
                        ->where('is_deleted','<>','1')
                        ->get();
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.asset_category.action', [
                    'model' => $model,
                    'url_show'=> route('asset-category.show', $model->id),
                    'url_edit'=> route('asset-category.edit', $model->id),
                    'url_destroy'=> route('asset-category.destroy', $model->id)
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
