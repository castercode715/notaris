<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstRegencies; 
use App\Models\MstRegenciesLang; 
use App\Models\MstProvinces; 
use App\Models\MstLanguage; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstRegenciesController extends Controller
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

        return view('master.regencies.index');
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

        $model = new MstRegencies();

        $language = MstLanguage::all();

        $provinces = MstProvinces::pluck('name','id')->all();

        return view('master.regencies.create', compact('model','language','provinces'));
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

        $validate = $this->validate($request,[
            'id' => 'required|unique:mst_regencies,id',
            'province' => 'required',
            'name' => 'required|array'
        ]);

        if($validate)
        {
            $data = [
                'id' => $request->id,
                'provinces_id' => $request->province
            ];

            if($model = MstRegencies::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    $data = [
                        'regencies_id' => $model->id,
                        'code' => $value,
                        'name' => $request->name[$key]
                    ];
                    MstRegenciesLang::create($data);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/regencies/'. base64_encode($model->id) )->with('success', 'Created successfully');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
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
            
        $id = base64_decode($id);

        $regencies = MstRegencies::find($id);

        $model = DB::table('mst_regencies as p')
                    ->join('mst_regencies_lang as pl','p.id','pl.regencies_id')
                    ->where('p.id', $id)
                    ->where('pl.code', 'IND')
                    ->select(
                        'pl.id as regencies_lang_id',
                        'pl.name',
                        'p.*'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();

        $province = MstProvinces::find($regencies->provinces_id);

        return view('master.regencies.detail', compact([
            'regencies', 
            'model', 
            'language', 
            'province', 
            'uti'
        ]));
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

        $id = base64_decode($id);

        $model = MstRegencies::findOrFail($id);

        $data = DB::table('mst_regencies_lang as a')
                ->join('mst_language as b','a.code','=','b.code')
                ->where('a.regencies_id', $id)
                ->select('a.id as id_reg','a.code','a.name','b.language')
                ->get();

        $provinces = MstProvinces::pluck('name','id')->all();

        return view('master.regencies.update', compact(['model','language','data','provinces']));
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

        $id = base64_decode($id);
        $validate = $this->validate($request,[
            'id' => 'required|unique:mst_regencies,id,'.$id,
            'provinces_id' => 'required',
            'name' => 'required|array'
        ]);

        if($validate)
        {
            $model = MstRegencies::findOrFail($id);

            $data = [
                'id'        => $request->id,
                'provinces_id'    => $request->provinces_id
            ];

            if($model->update($data))
            {
                foreach($request->id_reg as $key => $value)
                {
                    $regencies = MstRegenciesLang::findOrFail($value);

                    $data = [
                        'code' => $request->code[$key],
                        'name' => $request->name[$key]
                    ];

                    $regencies->update($data);
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/regencies/'. base64_encode($model->id) )->with('success','Success');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
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

        $id = base64_decode($id);

        DB::table('mst_regencies')->where('id', $id)->delete();
        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        if(DB::table('mst_regencies')->where('id', $id)->delete())
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/regencies')->with('success', 'Deleted');
        }
        else
            return redirect('master/regencies')->with('error', 'Failed');
    }

    public function dataTable()
    {
        // $model = MstRegencies::query();
        // $model->join('mst_regencies_lang','mst_regencies.id','mst_regencies_lang.regencies_id');
        // $model->join('mst_provinces','mst_regencies.provinces_id','mst_provinces.id');
        // $model->join('mst_language','mst_language.code','mst_regencies_lang.code');
        // $model->select('mst_regencies.id','mst_provinces.name as province','mst_regencies_lang.name as regency');
        // $model->where('mst_regencies_lang.code',"IND");
        // $model->orderBy('mst_regencies.id','desc');
        $model = DB::table('v_regencies')->orderBy('id','desc');
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.regencies.action', [
                    'model' => $model,
                    'url_show'=> route('regencies.show', $model->id),
                    'url_edit'=> route('regencies.edit', $model->id),
                    'url_destroy'=> route('regencies.destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTableDetail($id)
    {
        $model = MstRegenciesLang::query();
        $model->join('mst_language','mst_language.code','mst_regencies_lang.code');
        $model->select('mst_language.language','mst_regencies_lang.name');
        $model->where('regencies_id',$id);

        return DataTables::of($model)
            ->addIndexColumn()
            ->make(true);
    }
}
