<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceDetailModels; 
use App\Models\MServicesModels; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class ServiceCartControllers extends Controller
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

    public function getServiceName($id)
    {
        $result = DB::table('m_services as a')
                    ->where('a.id', $id)
                    ->first();

        echo $result->name;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $keterangan_service = null;
        $service_id = null;
        $category_id = null;
        $categories = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->get();

        $model = new ServiceDetailModels();
        return view('transaction.services.fsk.add_service', compact('model','categories', 'keterangan_service','service_id','category_id'));
    }

    public function fetch(Request $request)
    {
        $value  = $request->get('value');

        $data = DB::table('m_service_categories as a')
                ->join('m_services as b','b.id','a.service_id')
                ->where('a.category_id', $value)
                ->select('b.id','b.name')
                ->get();
        
        $return = "<option value=''>- Silahkan Pilih -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->id."'>".$row->name."</option>";
        }
        echo $return;
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
            'category_service'      => 'required|string',
            'service'               => 'required|string',
            'keterangan_service'    => 'required|string'
        ]);

        $model = MServicesModels::findOrFail($request->service);

        return response()->json([
            'service_id'        => $request->service,
            'service_name'      => $model->name,
            'keterangan'        => $request->keterangan_service,
            'status'            => true,
        ]);

      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $id = $request->id;
        $service_id = $request->service_id;
        $keterangan_service = $request->keterangan;

        $category = DB::table('m_service_categories as a')
                        ->where('a.service_id', $service_id)
                        ->first();

        $services = DB::table('m_service_categories as a')
                    ->join('m_services as b','b.id','=','a.service_id')
                    ->where('a.category_id', $category->category_id)
                    ->select('b.id', 'b.name')
                    ->get();

        $categories = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->get();

        $model = new ServiceDetailModels();
        return view('transaction.services.fsk.edit_service', compact('model','categories','keterangan_service','service_id','category','services','id'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
