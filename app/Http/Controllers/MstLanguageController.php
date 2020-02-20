<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstLanguage; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.language.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstLanguage();
        return view('master.language.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'code'          => 'required|string|unique:mst_language,code',
            'language'      => 'required|string'

        ]);

         $data = [
            'code'      => $request->code,
            'language'      => ucwords($request->language)
        ];

        MstLanguage::create($data);
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = base64_decode($id);
        $model = MstLanguage::findOrFail($id);
        $uti = new Utility();
        return view('master.language.detail', compact(['model', 'uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = MstLanguage::findOrFail($id);
        return view('master.language.create', compact('model'));
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
        $this->validate($request, [
            'code'  => 'required|string|unique:mst_language,code,'.$id.',code',
            'language'=> 'required'
        ]);

        $data = [
            'code'          => $request->code,
            'language'          => $request->language
        ];

        $model = MstLanguage::findOrFail($id);
        $model->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
        DB::table('mst_language')->where('code', '=', $id)->delete();
    }

    public function dataTable()
    {
        $model = MstLanguage::query();
        // $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.language.action', [
                    'model' => $model,
                    'url_show'=> route('language.show', base64_encode($model->code)),
                    'url_edit'=> route('language.edit', base64_encode($model->code)),
                    'url_destroy'=> route('language.destroy', base64_encode($model->code))
                ]);
            })
           
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }
}
