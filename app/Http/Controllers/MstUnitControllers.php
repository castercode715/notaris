<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstUnitModels; 
use App\Models\MstAptAssetModels; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstUnitControllers extends Controller
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

    public function createUnit($id)
    {

        $model = new MstUnitModels();
        $code = base64_decode($id);
        return view('master.apt-asset.unit.create',compact('model','code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstUnitModels();
        return view('master.apt-asset.unit.create',compact('model'));
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
            'name'              => 'required|string',
            'code_floor'        => 'required|string',
        ]);

        $uti = new Utility;
        $code = $request->code_floor."".$uti->SquanceCode($request->code_floor);
        $code_apttt = substr($request->code_floor, 0, 2);
        $data = [
            'code_unit'         => $code,
            'name'              => ucwords($request->name),
            'code_floor'        => $request->code_floor,
            'is_deleted'        => 0,
        ];

        // dd($data);

        $model = MstUnitModels::create($data);   

        if($model){
            // Cek total unit
            $cek = MstAptAssetModels::findOrFail($code_apttt);
            if($cek){
                $total_unit = $cek->total_unit + 1;
                $remaining_unit = $cek->remaining_unit + 1;

                $update = DB::update("update mst_apt_asset set 
                                    total_unit = ".$total_unit.",
                                    remaining_unit = ".$remaining_unit."
                                    where code_apt = ".$code_apttt."");

                return $model;
            }   

        }
        
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
        $model = MstUnitModels::findOrFail($id);
        $uti = new Utility();
        return view('master.apt-asset.unit.detail', compact(['model', 'uti']));
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
        $model = MstUnitModels::findOrFail($id);
        $code = $model->code_floor;
        return view('master.apt-asset.unit.create', compact('model','code'));
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
            'name'              => 'required|string',
            'code_floor'        => 'required|string',
        ]);

        $uti = new Utility;
        $model = MstUnitModels::findOrFail($id);

        $data = [
            'name'              => ucwords($request->name),
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
        $id = base64_decode($id);
        $a = MstUnitModels::findOrFail($id);

        $delete = DB::update("update mst_unit_apt set 
            code_unit = '".$a->code_unit."D',
            is_deleted = 1
            where code_unit = '".$a->code_unit."'");

        if($delete){
            
            $code = substr($a->code_floor, 0, 2);
            $cek = MstAptAssetModels::findOrFail($code);

            if($cek){
                $total_unit = $cek->total_unit - 1;
                $remaining_unit = $cek->remaining_unit - 1;

                $update = DB::update("update mst_apt_asset set 
                                    total_unit = ".$total_unit.",
                                    remaining_unit = ".$remaining_unit."
                                    where code_apt = ".$code."");
            } 


        }
        // DB::table('mst_unit_apt')->where('code_unit', '=', $id)->delete();
    }
}
