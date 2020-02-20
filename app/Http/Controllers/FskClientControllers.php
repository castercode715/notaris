<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MClientModels;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class FskClientControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MClientModels();
        return view('transaction.services.fsk.create-client', compact(['model']));
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
            'name'  => 'required',
            'email'  => 'required|email',
            'telephone_number'  => 'required',
            'handphone_number'  => 'required',
            'client_flag'  => 'required',
        ]);

        $userId = Auth::user()->id;
        $uti = new Utility();

        $data = [
            'notaris_id'        => $uti->getUserNew(),
            'name'              => $request->name,
            'email'             => $request->email,
            'telephone_number'  => $request->telephone_number,
            'handphone_number'  => $request->handphone_number,
            'client_flag'       => $request->client_flag,
            'created_by'        => $userId,
            'updated_by'        => $userId
        ];

        
        if($model = MClientModels::create($data)){
            return response()->json([
                'id' => $model->id,
                'name' => $model->name,
                'status' => true,
            ]);

        }else{

            return response()->json([
                'status' => false,
            ]);
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
