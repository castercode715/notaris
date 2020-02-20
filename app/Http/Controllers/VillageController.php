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




class VillageController extends Controller
{
    const MODULE_NAME = 'Village';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $village = MstVillages::all();
		return view('country.village.index',compact('village'));
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

		$district = MstDistricts::all();
        return view('country.village.create',compact('district'));
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
			return redirect('/master/village/create')->withInput()->withErrors($validator);
	   }
	   
	   $dist = new MstVillages();
       $dist->district_id = $request->district_id;
       $dist->name = $request->name;
	   $dist->save();
	   return redirect('/master/village');

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

        $district = MstDistricts::all();
        $data = MstVillages::findOrFail($id);
        return view('country.village.edit',compact('data','district'));
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
        
	   $reg = MstVillages::findOrFail($id);
	   $reg->district_id = $request->district_id;
	   $reg->name = $request->name;
	   $reg->save();
	   
	   return redirect('/master/village');
	   
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
        
		$delete = MstVillages::findOrFail($id);
 		$delete->delete();
		return redirect('/master/village');
		
    }
	
	public function import()
    {
			$district = MstDistricts::all();
			return view('country.village.import',compact('districts'));
		
		
    }
	
	public function import_village(Request $request)
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
					'district_id' => $request->district_id,
                    'name' => $data[0][$i]['name']
                    ];
                } 
				DB::table('mst_provinces')->insert($insert);
				return redirect('/master/village');
                }
				
			}	
		}
	}
}
