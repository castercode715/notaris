<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstDenahModels; 
use Illuminate\Support\Facades\Auth;
use DB;
use DataTables;
use App\Utility;


class MstDenahAptControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function createFloor($id)
    {
        $model = new MstDenahModels();
        $code = $id;
        return view('master.apt-asset.floor.create',compact('model','code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'code_apt'          => 'required'
        ]);

        $userId = Auth::user()->id;
        $uti = new Utility;

        $data = [
            'code_floor'        => base64_decode($request->code_apt)."".$uti->SquanceCodeFloor(),
            'name'              => $request->name,
            'denah'             => $request->denah,
            'code_apt'          => base64_decode($request->code_apt),
        ];

        $model = MstDenahModels::create($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = base64_decode($id);
        $model = MstDenahModels::findOrFail($id);

        return view('master.apt-asset.floor.detail', compact(['model']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = MstDenahModels::findOrFail($id);

        $code = $model->code_apt;

        return view('master.apt-asset.floor.create', compact(
            'model',
            'code'
        ));
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
        $request->validate([
            'name'              => 'required',
            'code_apt'          => 'required'
        ]);

        $model = MstDenahModels::findOrFail($id);
        $userId = Auth::user()->id;

        if($request->denah != null){
            $data = [
                'name'              => $request->name,
                'denah'             => $request->denah,
                'code_apt'          => $request->code_apt,
            ];
        }else{
            $data = [
                'name'              => $request->name,
                'code_apt'          => $request->code_apt,
            ];
        }

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
        $a = ['mst_unit_apt'];
        $b = ['code_floor'];

        $id = base64_decode($id);
        if(!$this->isAllowedToUpdate($a, $b, $id)){
            abort(400, 'Table has related.');
        }

        $delete = DB::table('mst_floor_apt')->where('code_floor', '=', $id)->delete();
        
    }

    public function cekData(Request $request)
    {
        // return json_encode(array("status" => $request->code_apt));
        dd($request->code_apt);
    }

    public function dataTable($id)
    {
        $model = DB::table('mst_floor_apt as a')
                    ->where('a.code_apt', $id)
                    ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.apt-asset.floor.action', [
                    'model' => $model,
                    'url_show'=> route('floor.show', base64_encode($model->code_floor)),
                    'url_edit'=> route('floor.edit', base64_encode($model->code_floor)),
                    'url_destroy'=> route('floor.destroy', base64_encode($model->code_floor))
                ]);
            })

            ->addIndexColumn()
            ->rawColumns(['active', 'action'])
            ->make(true);
    }

    public function dataFloorUnit($id)
    {
        $model = DB::table('mst_unit_apt as a')
                    ->where('a.code_floor', $id)
                    ->where('a.is_deleted', 0)
                    ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.apt-asset.unit.action', [
                    'model' => $model,
                    'url_show'=> route('unit-asset.show', base64_encode($model->code_unit)),
                    'url_edit'=> route('unit-asset.edit', base64_encode($model->code_unit)),
                    'url_destroy'=> route('unit-asset.destroy', base64_encode($model->code_unit))
                ]);
            })

            

            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by','status','type_apt'])
            ->make(true);
    }
}
