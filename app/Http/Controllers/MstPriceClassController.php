<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPriceClass;
use App\Models\MstClass;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class MstPriceClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return view('master.class_price.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstPriceClass();
        $page = MstClass::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('name', 'id')
                    ->all();

        return view('master.class_price.create', compact(['model','page']));
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
            'class_id'  => 'required',
            'price_start'  => 'required',
            'price_end'  => 'required'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;
       
        $data = [
            'class_id'      => $request->class_id,
            'price_start'   => $request->price_start,
            'price_end'     => $request->price_end,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = MstPriceClass::create($data);
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
        $model = DB::table('mst_class as a')
                    ->join('mst_price_class as b','a.id','=','b.class_id')
                    ->where('b.id',$id)
                    ->select('a.*','b.*','b.class_id as nm')
                    ->first();
        $uti = new Utility();
        return view('master.class_price.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MstPriceClass::findOrFail($id);
        $page = MstClass::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('name', 'id')
                    ->all();
        
        return view('master.class_price.create', compact(['model','page']));
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
            'class_id'  => 'required',
            'price_start'  => 'required',
            'price_end'  => 'required'
        ]);

       $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;
       
        $data = [
            'class_id'      => $request->class_id,
            'price_start'   => $request->price_start,
            'price_end'     => $request->price_end,
            'active'        => $active,
            'created_by'    => $userId,
            'updated_by'    => $userId
        ];

        $model = MstPriceClass::findOrFail($id);
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

        DB::update("update mst_price_class set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable()
    {
        $model = MstPriceClass::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.class_price.action', [
                    'model' => $model,
                    'url_show'=> route('price-class.show', $model->id),
                    'url_edit'=> route('price-class.edit', $model->id),
                    'url_destroy'=> route('price-class.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->editColumn('class_id', function($model){
                //$page = SecModule::where('id', $model->module_id)->first();
                $page = MstClass::findOrFail($model->class_id);
                return $page->name;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }
}
