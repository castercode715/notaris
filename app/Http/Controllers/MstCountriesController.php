<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstCountries; 
use App\Models\MstLanguage; 
use App\Models\MstCurrency; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstCountriesController extends Controller
{
    const MODULE_NAME = 'Country';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.country.index');
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

        $model = new MstCountries();

        $language = MstLanguage::pluck('language','code')->all();

        $currency = MstCurrency::pluck('currency','code')->all();

        return view('master.country.create', compact('model','language','currency'));
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
            'id' => 'required',
            'language_code' => 'required',
            'currency_code' => 'required',
            'name' => 'required|string'
        ]);

        if($validate)
        {
            $model = DB::table('mst_countries')->insert([
                'id' => $request->id,
                'language_code' => $request->language_code,
                'currency_code' => $request->currency_code,
                'name' => $request->name
            ]);

            \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
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

        $model = MstCountries::findOrFail($id);
        return view('master.country.detail', compact('model'));
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

        $model = MstCountries::findOrFail($id);

        $language = MstLanguage::pluck('language','code')->all();

        $currency = MstCurrency::pluck('currency','code')->all();

        return view('master.country.create', compact('model','language','currency'));
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

        $validate = $this->validate($request,[
            'language_code' => 'required',
            'currency_code' => 'required',
            'name' => 'required|string'
        ]);

        if($validate)
        {
            $model = DB::table('mst_countries')
                ->where('id', $id)
                ->update([
                    'language_code' => $request->language_code,
                    'currency_code' => $request->currency_code,
                    'name' => $request->name
                ]);

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

        $model = MstCountries::findOrFail($id);
        $model->delete();

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = MstCountries::query();
        $model->join('mst_language','mst_language.code','mst_countries.language_code');
        $model->join('mst_currency','mst_currency.code','mst_countries.currency_code');
        $model->select('mst_countries.id','mst_countries.name','mst_language.language','mst_currency.currency');
        $model->orderBy('id','desc');
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.country.action', [
                    'model' => $model,
                    'url_show'=> route('countries.show', $model->id),
                    'url_edit'=> route('countries.edit', $model->id),
                    'url_destroy'=> route('countries.destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
