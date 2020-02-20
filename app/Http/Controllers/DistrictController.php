<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\MstCountries;
use App\Models\MstRegencies;
use App\Models\MstDistricts;
use App\Models\MstProvinces;
use App\Models\MstVillages;
use Excel;
use File;
use DB;



class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
		$district = MstDistricts::all();
		return view('country.district.index',compact('district'));

		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$regency = MstRegencies::all();
        return view('country.district.create',compact('regency'));
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
	   ]);		
	   
	   if($validator->fails()){
			return redirect('/master/regency/create')->withInput()->withErrors($validator);
	   }
	   
	   $dist = new MstDistricts();
       $dist->regency_id = $request->regency_id;
       $dist->name = $request->name;
	   $dist->save();
	   return redirect('/master/district');
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
        $regency = MstRegencies::all();
        $data = MstDistricts::findOrFail($id);
        return view('country.district.edit',compact('data','regency'));
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
       $reg = MstDistricts::findOrFail($id);
	   $reg->regency_id = $request->regency_id;
	   $reg->name = $request->name;
	   $reg->save();
	   
	   return redirect('/master/district');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = MstDistricts::findOrFail($id);
 		$delete->delete();
		return redirect('/master/district');
    }
	
	
	public function import()
    {
			$regency = MstRegencies::all();
			return view('country.district.import',compact('regency'));
		
		
    }
	
	public function import_district(Request $request)
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
					'regency_id' => $request->regency_id,
                    'name' => $data[0][$i]['name']
                    ];
                } 
				DB::table('mst_districts')->insert($insert);
				return redirect('/master/regency');
                }
				
			}	
		}
	}
}
