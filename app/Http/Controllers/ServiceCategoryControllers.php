<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategoryModels;
use App\Models\MCategoryModels;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class ServiceCategoryControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.service-category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $services = DB::table('m_services as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $service = NULL;

        $model = new ServiceCategoryModels();
        return view('master.service-category.create', compact('model','services','categories','service'));
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
            'service.*'       => 'required|string',
            'category_id'          => 'required|string'
        ]);

        foreach($request->service as $value)
        {
            $data = [
                'service_id' => $value,
                'category_id' => $request->category_id
            ];
            ServiceCategoryModels::create($data);
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
        $model = ServiceCategoryModels::findOrFail($id);

        $service = DB::table('m_service_categories as a')
                    ->join('m_category as b','b.id','=','a.category_id')
                    ->where('a.category_id', $model->category_id)
                    ->select('b.id','b.name as category')
                    ->first();

        $uti = new Utility();
        return view('master.service-category.detail', compact(['model','uti','service']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ServiceCategoryModels::findOrFail($id);
        $categories = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $services = DB::table('m_services as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $service = ServiceCategoryModels::where('category_id',$model->category_id)
                    ->pluck('service_id')
                    ->toArray();

        return view('master.service-category.create', compact('model','categories','services','service'));
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
        $this->validate($request,[
            'service.*'       => 'required|string',
            'category_id'          => 'required|string'
        ]);

        $model = ServiceCategoryModels::findOrFail($id);
        if($request->service != null){
            
            $cek = DB::table('m_service_categories as a')
                    ->where('a.category_id', $model->category_id)
                    ->count();

                if ($cek > 0) {
                    
                    $delete = ServiceCategoryModels::where('category_id',$model->category_id)->delete();

                    if ($delete) {

                        foreach($request->service as $value)
                        {
                            $data = [
                                'service_id' => $value,
                                'category_id' => $request->category_id
                            ];
                            ServiceCategoryModels::create($data);
                        }
                    }
                }else{
                    foreach($request->service as $value)
                    {
                        $data = [
                            'service_id' => $value,
                            'category_id' => $request->category_id
                        ];
                        ServiceCategoryModels::create($data);
                    }
                }
        }else{
            $delete = ServiceCategoryModels::where('category_id',$model->category_id)->delete();
            
            $data = [
                'service_id' => '',
                'category_id' => $request->category_id
            ];
            ServiceCategoryModels::create($data);
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
        $model = ServiceCategoryModels::findOrFail($id);
        $delete = ServiceCategoryModels::where('category_id',$model->category_id)->delete();
    }

    public function dataTable()
    {
        $model = DB::table('m_service_categories as a')
                ->join('m_category as b','b.id','=','a.category_id')
                ->join('m_services as c','c.id','=','a.service_id')
                ->select('a.id','c.name as service','b.name as category')
                ->groupBy('category')
                ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.service-category.action', [
                    'model' => $model,
                    'url_show'=> route('service-category.show', $model->id),
                    'url_edit'=> route('service-category.edit', $model->id),
                    'url_destroy'=> route('service-category.destroy', $model->id)
                ]);
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }

    public function categoryRelated($id)
    {
        $model = DB::table('m_service_categories as a')
                ->join('m_services as b','b.id','=','a.service_id')
                ->where('a.category_id', $id)
                ->select('a.id', 'b.name as service')
                ->get();

        // dd($id);
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.service-document.action_detail', [
                    'model' => $model,
                    'url_edit'=> route('service-document.edit', $model->id)
                ]);
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }
}
