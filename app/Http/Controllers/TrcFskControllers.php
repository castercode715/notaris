<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrcServiceModels; 
use App\Models\MClientModels; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class TrcFskControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getCategory(Request $request)
    {
        $result = MClientModels::find($request->id);

        return $result;
    }

    public function index()
    {
        $model = new TrcServiceModels;
        return view('transaction.services.fsk.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $model = new TrcServiceModels();
        $category = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->get();

        $fsk_no = $model->Squance();

        $client = DB::table('m_client as a')
                    ->where('a.is_deleted', 0)
                    ->get();

        $debitur = DB::table('m_client as a')
                    ->where('a.is_deleted', 0)
                    ->where('a.client_flag', 'perorangan')
                    ->get();

        return view('transaction.services.fsk.create', compact('model','category','client','fsk_no','debitur'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
