<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Auth;
use App\Models\MstCountries;
use App\Models\MstAddress;
use App\Models\MstRegencies;
use App\Models\MstDistricts;
use App\Models\MstProvinces;
use App\Models\MstVillages;
use DB;


class MstAddressController extends Controller
{
    /**
     * Display a listing of the resource. 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MstAddress::all();
		return view('country.address.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
		$country = MstCountries::all();
		$province = MstProvinces::all();
		$regency = MstRegencies::all();
		$district = MstDistricts::all();
		$village = MstVillages::all();
        return view('country.address.create',compact('country','province','regency','district','village'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
	public function province($id){

	$data = DB::select("select * from mst_provinces where country_id = '".$id."'");
	
	echo"<div class='form-group'>
			<label for='name'> Province <span class='required'>*</span></label>
			<select name='province' class='form-control'  id='regency' onChange='regencynya(this.value)'>
			<option value=''>Pilih Province</option>
			";
			
					foreach($data as $row){
					echo "<option value=".$row->id.">".$row->name."</option>";
					}	
					
			echo"</select>
		</div>";
	
	
	
	}


	public function regency($id){

	$data = DB::select("select * from mst_regencies where provinces_id = '".$id."'");
	
	echo'<div class="form-group">
			<label for="name"> Regency <span class="required">*</span></label>
			<select name="regency" class="form-control" id="district" onChange="districtnya(this.value)">
			<option value="">Pilih Regency</option>';
			
	foreach($data as $row){
	
					echo '<option value='.$row->id.'>'.$row->name.'</option>';
					
	}				
					
			echo'</select>
		</div>';
	
	
	
	}


	public function district($id){

	$data = DB::select("select * from mst_districts where regency_id = '".$id."'");
	
	
	
	echo'<div class="form-group">
			<label for="name"> District <span class="required">*</span></label>
			<select name="district" class="form-control" id="village" onchange="villagenya(this.value)">
			<option value="">Pilih district</option>
			';
			foreach($data as $row){
				echo '<option value='.$row->id.'>'.$row->name.'</option>';
			}	
		echo'		
			</select>
		</div>';
	
	}


	public function village($id){

	$data = DB::select("select * from mst_villages where district_id = '".$id."'");
	
	
	
	echo'<div class="form-group">
			<label for="name"> District <span class="required">*</span></label>
			<select name="village" class="form-control">
			<option value="">Pilih Village</option>
			';
			foreach($data as $row){
				echo '<option value='.$row->id.'>'.$row->name.'</option>';
			}	
		echo'		
			</select>
		</div>';
	
	}


	
	 
    public function store(Request $request)
    {
     
	   $add = new MstAddress();
       $add->country_id = $request->country;
       $add->province_id = $request->province;
       $add->regency_id = $request->regency;
       $add->district_id = $request->district;
       $add->village_id = $request->village;
	   $add->save();
	   return redirect('/master/data_address');
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
	
		$delete = MstAddress::findOrFail($id);
 		$delete->delete();
		return redirect('/master/address');
        
    }
}
