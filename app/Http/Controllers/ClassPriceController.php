<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPriceClass;
use App\Models\MstClass;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Validator;
use DB;

class ClassPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('mst_price_class')
		->join('mst_class', 'mst_price_class.class_id', '=', 'mst_class.id')
		->join('mst_employee', 'mst_price_class.created_by', '=', 'mst_employee.id')
		->select('mst_price_class.*', 'mst_class.name', 'mst_employee.username')
		->get();
        //$user = auth()->user();
        return view('master.class_price.index', compact('data'));
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
		$data = MstClass::all();
        return view('master.class_price.create',compact('data'));
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
		
	$input = $request->all();
		
	   $validator = Validator::make($input,[
	   
	   'class_id'=>'required',
	   'price_start'=>'required',
	   'price_end'=>'required',
	   
	   ]);		
	   
	   if($validator->fails()){
			return redirect('/master/class_price/create')->withInput()->withErrors($validator);
	   }
	   
	   $userId = Auth::user()->id;
	   
	   $price = new MstPriceClass();
       $price->class_id = $request->class_id;
       $price->price_start = $request->price_start;
       $price->price_end = $request->price_end;
       $price->created_by = $userId;
	   $price->save();
	   return redirect('/master/class_price');	
		
		
		
		
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	
        //$data = MstPriceClass::findOrFail($id);
		
		 $data = DB::table('mst_price_class')
		->join('mst_class', 'mst_price_class.class_id', '=', 'mst_class.id')
		->join('mst_employee', 'mst_price_class.created_by', '=', 'mst_employee.id')
		->select('mst_price_class.*', 'mst_class.name', 'mst_employee.username')
		->where('mst_price_class.id','=',$id)
		->get();
		
        return view('master.class_price.detail',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	
		$datas = MstClass::all();
        $data = MstPriceClass::findOrFail($id);
      
	    return view('master.class_price.edit', compact('data','datas'));
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
	   $userId = Auth::user()->id;	
       $price = MstPriceClass::findOrFail($id);
	   $price->class_id = $request->class_id;
       $price->price_start = $request->price_start;
       $price->price_end = $request->price_end;
       $price->updated_by = $userId;
	   $price->save();
	   
	    return redirect('/master/class_price');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	
		$delete = MstPriceClass::findOrFail($id);
 		$delete->delete();
		return redirect('/master/class_price');
    }
	
	
}
