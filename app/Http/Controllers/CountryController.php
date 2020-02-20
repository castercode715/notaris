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

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
		$country  = MstCountries::all();
		return view('country.country.index',compact('country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
			return view('country.country.create');
		
		
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
			return redirect('/master/province/create')->withInput()->withErrors($validator);
	   }
	   
	   $country = new MstCountries();
       $country->name = $request->name;
	   $country->save();
	   return redirect('/master/country');
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
     
		$data = MstCountries::findOrFail($id);
        return view('country.country.edit',compact('data'));
	 
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
       $prov = MstCountries::findOrFail($id);
	   $prov->name = $request->name;
	   $prov->save();
	   
	   return redirect('/master/country');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = MstCountries::findOrFail($id);
 		$delete->delete();
		return redirect('/master/country');
    }
	
	
	public function import()
    {
        
			return view('country.country.import');
		
		
    }
	
	
	public function proses_import(Request $request)
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
                    'name' => $data[0][$i]['name']
                    ];
                } 
				DB::table('mst_countries')->insert($insert);
				return redirect('/master/country');
                }
				
			}	
		}
	}
}