<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\MstCountries;
use App\Models\MstRegencies;
use App\Models\MstDistricts;
use App\Models\MstProvinces;
use App\Models\MstVillages;
use Excel;
use File;
use DB;



class RegencyController extends Controller
{
    const MODULE_NAME = 'Regency';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = DB::table('mst_provinces as a')
        ->join('mst_regencies as b','b.provinces_id','=','a.id')
        ->select('b.id','a.name as provin','b.name')
        ->orderBy('b.id','desc')
        ->get();
		return view('country.regency.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

    	$regency = MstProvinces::all();
        return view('country.regency.create',compact('regency'));
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
	   'name'=>'required',
		'id'=>'required',	
	   ]);		
	   
	   if($validator->fails()){
			return redirect('/master/regency/create')->withInput()->withErrors($validator);
	   }
	   
	   $country = new MstRegencies();
       $country->provinces_id = $request->province_id;
       $country->id = $request->id;
	$country->name = $request->name;
	   $country->save();
	   return redirect('/master/regency');
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
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

		$regency = MstProvinces::all();
        $data = MstRegencies::findOrFail($id);
        return view('country.regency.edit',compact('data','regency'));
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
     
	   $reg = MstRegencies::findOrFail($id);
	   $reg->provinces_id = $request->province_id;
	   $reg->name = $request->name;
	   $reg->save();
	   
	   return redirect('/master/regency');
	 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $delete = MstRegencies::findOrFail($id);
 		$delete->delete();
		return redirect('/master/regency');
    }
	
	public function import()
    {
			$regency = MstProvinces::all();
			return view('country.regency.import',compact('regency'));
		
		
    }
	
	
	public function import_regency(Request $request)
    {
        
		if($request->hasFile('file')){
        $extension = File::extension($request->file->getClientOriginalName());
        if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
            $path = $request->file->getRealPath();
            $data = Excel::load($path, function($reader){
            })->get();
            if(!empty($data) && $data->count()){
 
				$jumlah = count($data);
                for ($i=0;$i<$jumlah;$i++) {
                    $insert[] = [
					'provinces_id' => $request->province_id,
                    'name' => $data[0][$i]['name']
                    ];
                } 
				DB::table('mst_regencies')->insert($insert);
				return redirect('/master/regency');
                }
				
			}	
		}
	}
}
