<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrcKprModels; 
use App\Models\KprAsset; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class TrcInstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new TrcKprModels;
        return view('kpr.installment.index', compact('model'));
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

    public function dataTable()
    {
        $model = TrcKprModels::query();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('kpr.installment.action', [
                    'model' => $model,
                    'url_show'=> route('installment.show', $model->app_number),
                    'url_edit'=> route('installment.edit', $model->app_number),
                    'url_destroy'=> route('installment.destroy', $model->app_number)
                ]);
            })
            ->editColumn('booked_date', function($model){
                return date('d/m/Y', strtotime($model->booked_date));
            })
            ->editColumn('code_kpr', function($model){
                $result = KprAsset::findOrFail($model->code_kpr);
                return $result->name;
            })
            ->editColumn('investor_id', function($model){
                $uti = new utility();
                return $uti->investor($model->investor_id);
            })
            ->editColumn('status', function($model){
                $msg = "";
                if($model->status == "NEW"){
                    $msg = "<span class='label label-success'>NEW</span>";
                }else if($model->status == "ACCEPT"){
                    $msg = "<span class='label label-info'>ACCEPT</span>";
                }else if($model->status == "REJECT"){
                    $msg = "<span class='label label-warning'>REJECT</span>";
                }else if($model->status == "CANCEL"){
                    $msg = "<span class='label label-danger'>CANCEL</span>";
                }else{
                    $msg = "<span class='label label-default'>SURVEY</span>";
                }

                return $msg;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'code_kpr', 'booked_date', 'created_by','investor_id','status'])
            ->make(true);
    }
}
