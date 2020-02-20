<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\MstCountries;
use App\Models\MstProvinces;
use App\Models\MstRegencies;
use App\Models\MstRegenciesLang;
use App\Models\MstDistricts;
use App\Models\MstVillages;
use App\Utility;
use Excel;
use File;
use DB;
use DataTables;

class MstVillageController extends Controller
{
    const MODULE_NAME = 'village';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

		return view('master.village.index');
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

        $model = new MstVillages();

        $countries = MstCountries::pluck('name','id')->all();

        return view('master.village.create', compact(['countries','model']));
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

        $validate = $this->validate($request, [
            'country' => 'required',
            'mst_provinces' => 'required',
            'mst_regencies'  => 'required',
            'mst_districts'  => 'required',
            'id'  => 'required|unique:mst_villages,id',
            'name'  => 'required|string'
        ]);

        if($validate)
        {
            $data = [
                'districts_id' => $request->mst_districts,
                'id' => $request->id,
                'name' => $request->name
            ];

            $model = MstVillages::create($data);
            \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
            echo $model->id;
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
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $model = MstVillages::join('mst_districts','mst_villages.districts_id','mst_districts.id')
                ->join('mst_regencies','mst_districts.regencies_id','mst_regencies.id')
                ->join('mst_provinces','mst_regencies.provinces_id','mst_provinces.id')
                ->join('mst_countries','mst_provinces.countries_id','mst_countries.id')
                ->where('mst_villages.id', $id)
                ->select(
                    'mst_villages.id',
                    'mst_villages.name',
                    'mst_villages.districts_id as mst_districts',
                    'mst_regencies.id as mst_regencies',
                    'mst_provinces.id as mst_provinces',
                    'mst_countries.id as country'
                )
                ->first();

        $countries = MstCountries::pluck('name','id')->all();

        $provinces = MstProvinces::where('countries_id',$model->country)->pluck('name','id')->all();

        $regencies = MstRegencies::join('mst_regencies_lang','mst_regencies_lang.regencies_id','mst_regencies.id')
                    ->where('mst_regencies.provinces_id',$model->mst_provinces)
                    ->where('mst_regencies_lang.code',"IND")
                    ->pluck('mst_regencies_lang.name','mst_regencies.id')
                    ->all();

        $districts = MstDistricts::where('regencies_id',$model->mst_regencies)->pluck('name','id')->all();

        return view('master.village.create', compact([
            'model',
            'countries',
            'provinces',
            'regencies',
            'districts'
        ]));
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

        $validate = $this->validate($request, [
            'country' => 'required',
            'mst_provinces' => 'required',
            'mst_regencies'  => 'required',
            'mst_districts'  => 'required',
            'id'  => 'required|unique:mst_villages,id,'.$id,
            'name'  => 'required|string'
        ]);

        if($validate)
        {
            $model = MstVillages::findOrFail($id);
            $data = [
                'districts_id' => $request->mst_districts,
                'id' => $request->id,
                'name' => $request->name
            ];

            $model->update($data);
            \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
            echo $id;
        }
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
        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
		echo 0;
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

    public function dataTable()
    {
        $model = DB::table('mst_villages as a')
                ->join('mst_districts as b','a.districts_id','=','b.id')
                ->select('a.id','b.name as district','a.name')
                // ->limit('10')
                ->orderBy('a.id','desc')
                ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.village.action', [
                    'model' => $model,
                    'url_show'=> route('village.show', $model->id),
                    'url_edit'=> route('village.edit', $model->id),
                    'url_destroy'=> route('village.destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function search(Request $request)
    {
        $id = $request->get('id');
        $name = $request->get('name');
        $data = [];
        if($id != null || $name != null)
        {
            $query = "SELECT
                country,
                province,
                regency,
                district,
                villages_id,
                village 
            FROM
                v_location ";
            if($id != null && $name != null)
                $query .= "WHERE villages_id like '%".$id."%' AND village like '%".$name."%'";
            elseif($id != null)        
                $query .= "WHERE villages_id like '%".$id."%'";
            elseif($name != null)        
                $query .= "WHERE village like '%".$name."%'";
            $data = DB::select($query);
        }
        
        return view('master.village.search', compact('data'));
    }

    public function searchr($id)
    {
        $data = DB::select("
            SELECT
                country,
                province,
                regency,
                district,
                villages_id,
                village 
            FROM
                v_location 
            WHERE villages_id = '".$id."'
        ");

        return view('master.village.search', compact('data'));
    }
}
