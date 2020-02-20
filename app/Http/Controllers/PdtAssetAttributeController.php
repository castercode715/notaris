<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdtAssetAttribute;
use App\Models\PdtCategoryAsset;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class PdtAssetAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.asset_attribute.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new PdtAssetAttribute();

        $category = PdtCategoryAsset::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('desc', 'id')
                    ->all();

        return view('master.asset_attribute.form', compact(['model','category']));
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
            'name'              => 'required|string',
            'category_asset_id' => 'required'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'name'          => $request->name,
            'category_asset_id'       => $request->category_asset_id,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = PdtAssetAttribute::create($data);
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
        // $model = PdtAssetAttribute::findOrFail($id);
        $model = DB::table('pdt_asset_attribute as a')
                ->join('pdt_category_asset as b','a.category_asset_id','=','b.id')
                ->where('a.id',$id)
                ->select('a.*','b.desc as category')
                ->first();

        $uti = new Utility;

        return view('master.asset_attribute.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = PdtAssetAttribute::findOrFail($id);

        $category = PdtCategoryAsset::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('desc', 'id')
                    ->all();

        return view('master.asset_attribute.form', compact(['model','category']));
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
            'name'              => 'required|string',
            'category_asset_id' => 'required'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'name'          => $request->name,
            'category_asset_id'       => $request->category_asset_id,
            'active'        => $active,
            'updated_by'    => $userId
        ];

        $model = PdtAssetAttribute::findOrFail($id);
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

        DB::update("update pdt_asset_attribute set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable()
    {
        $model = PdtAssetAttribute::query();
        $model->where('is_deleted','<>','1');

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.asset_attribute.action', [
                    'model' => $model,
                    'url_show'=> route('asset-attribute.show', $model->id),
                    'url_edit'=> route('asset-attribute.edit', $model->id),
                    'url_destroy'=> route('asset-attribute.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addColumn('category', function($model){
                $category = PdtCategoryAsset::where('id', $model->category_asset_id)->first();
                return $category->desc;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'category', 'active', 'create_at', 'created_by'])
            ->make(true);
    }
}
