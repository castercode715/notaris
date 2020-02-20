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



class ProvinceController extends Controller
{
    const MODULE_NAME = 'Province';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        $province = MstProvinces::all();
		return view('country.province.index',compact('province'));
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

		$country = MstCountries::all();
        return view('country.province.create',compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');
        
	   $input = $request->all();
		
	   $validator = Validator::make($input,[
	   
	   'name'=>'required',
	   
	   ]);		
	   
	   if($validator->fails()){
			return redirect('/master/province/create')->withInput()->withErrors($validator);
	   }
	   
	   $prov = new MstProvinces();
       $prov->country_id = $request->country_id;
       $prov->name = $request->name;
	   $prov->save();

       \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$prov->id.' Successfully');
	   return redirect('/master/province');	
				
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

		$country = MstCountries::all();	
        $data = MstProvinces::findOrFail($id);
        return view('country.province.edit',compact('data','country'));
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
       if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

       $prov = MstProvinces::findOrFail($id);
	   $prov->country_id = $request->country_id;
	   $prov->name = $request->province_name;
	   $prov->save();
	   \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$prov->id.' Successfully');
	   return redirect('/master/province');
    
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

        $delete = MstProvinces::findOrFail($id);
 		$delete->delete();
        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
		return redirect('/master/province');
    }
	
	public function import()
    {
			$country = MstCountries::all();	
			return view('country.province.import',compact('country'));
		
		
    }
	
	public function import_province(Request $request)
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
					'country_id' => $request->country_id,
                    'name' => $data[0][$i]['name']
                    ];
                } 
				DB::table('mst_provinces')->insert($insert);
				return redirect('/master/province');
                }
				
			}	
		}
	}
}
