<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\MstCountries;
use App\Models\MstRegenciesLang;
use App\Models\MstRegencies;
use App\Models\MstDistricts;
use App\Models\MstProvinces;
use App\Models\MstVillages;
use Excel;
use File;
use DB;
use DataTables;


class MstDistrictController extends Controller
{
    const MODULE_NAME = 'Regency';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

		$model = MstDistricts::all();
		return view('master.district.index',compact('model'));
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

        $model = new MstDistricts();

        $countries = MstCountries::pluck('name','id')->all();

        return view('master.district.create',compact(['model','countries']));
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
            'id'  => 'required|unique:mst_districts,id',
            'name'  => 'required|string'
        ]);

        if($validate)
        {
            $data = [
                'regencies_id' => $request->mst_regencies,
                'id' => $request->id,
                'name' => $request->name
            ];

            $model = MstDistricts::create($data);
            \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
            return $model;
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
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $model = DB::table('mst_districts as d')
                ->join('mst_regencies_lang as rl','d.regencies_id','rl.regencies_id')
                ->join('mst_regencies as r','rl.regencies_id','r.id')
                ->join('mst_provinces as p','r.provinces_id','p.id')
                ->join('mst_countries as c','p.countries_id','c.id')
                ->select('d.id','d.name','rl.name as regency','p.name as province','c.name as country')
                ->where('rl.code',"IND")
                ->where('d.id',$id)
                ->first();

        return view('master.district.detail', compact('model'));
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

        // $model = MstDistricts::findOrFail($id);
        $model = MstDistricts::join('mst_regencies','mst_districts.regencies_id','mst_regencies.id')
                ->join('mst_provinces','mst_regencies.provinces_id','mst_provinces.id')
                ->join('mst_countries','mst_provinces.countries_id','mst_countries.id')
                ->where('mst_districts.id', $id)
                ->select(
                    'mst_districts.id',
                    'mst_districts.name',
                    'mst_districts.regencies_id as mst_regencies',
                    'mst_countries.id as country',
                    'mst_provinces.id as mst_provinces'
                )
                ->first();

        $countries = MstCountries::pluck('name','id')->all();

        $provinces = MstProvinces::where('countries_id',$model->country)->pluck('name','id')->all();

        $regencies = MstRegencies::join('mst_regencies_lang','mst_regencies_lang.regencies_id','mst_regencies.id')
                    ->where('mst_regencies.provinces_id',$model->mst_provinces)
                    ->where('mst_regencies_lang.code',"IND")
                    ->pluck('mst_regencies_lang.name','mst_regencies.id')
                    ->all();

        return view('master.district.create',compact('model','countries','provinces','regencies'));
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
            'id'  => 'required|unique:mst_districts,id,'.$id,
            'name'  => 'required|string'
        ]);

        if($validate)
        {
            $model = MstDistricts::findOrFail($id);
            $data = [
                'regencies_id' => $request->mst_regencies,
                'id' => $request->id,
                'name' => $request->name
            ];

            $model->update($data);
            \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
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

        $delete = MstDistricts::findOrFail($id);
 		$delete->delete();
        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }
	
	
	public function import()
    {
			$regency = MstRegencies::all();
			return view('master.district.import',compact('regency'));
		
		
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

    public function dataTable()
    {
        /*$model = DB::table('mst_districts as a')
                ->join('mst_regencies as b','a.regencies_id','=','b.id')
                ->select('a.id','b.name as regency','a.name')
                ->orderBy('a.id','desc')
                ->get();*/
        $model = DB::table('v_district')->orderBy('id','desc');

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.district.action', [
                    'model' => $model,
                    'url_show'=> route('district.show', $model->id),
                    'url_edit'=> route('district.edit', $model->id),
                    'url_destroy'=> route('district.destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->make(true);
    }
}
