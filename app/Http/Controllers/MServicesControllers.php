<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MServicesModels;
use App\Models\ServiceCategoryModels;
use App\Models\ServiceDocumentModels;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;


class MServicesControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.service.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MServicesModels();
        $categories = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $documents = DB::table('m_documents as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $document = NULL;
        $category = NULL;
        return view('master.service.create', compact('model','categories','category','document','documents'));
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
            'name'          => 'required|string|unique:m_services,name,1,is_deleted',
            // 'sort'          => 'required|numeric|unique:m_services,sort,1,is_deleted',
            'category_id.*'   => 'required|string',
            'document.*'    => 'required|string'
        ]);

        $userId = Auth::user()->id;

        // Save Master Services
        $data = [
            'name'      => $request->name,
            // 'sort'      => $request->sort,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];
        
        $service = MServicesModels::create($data);
        if($service){

            // Save Category Service
            foreach($request->category_id as $value)
            {
                $data = [
                    'service_id' => $service->id,
                    'category_id' => $value
                ];
                ServiceCategoryModels::create($data);
            }
            
            // Save Documents Service
            foreach($request->document as $value)
            {
                $data = [
                    'service_id' => $service->id,
                    'document_id' => $value
                ];
                ServiceDocumentModels::create($data);
            }
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
        $model = MServicesModels::findOrFail($id);
        $category = DB::table('m_service_categories as a')
                    ->join('m_category as b','b.id','=','a.category_id')
                    ->where('a.service_id', $model->id)
                    ->select('b.id','b.name as category')
                    ->get();

        $uti = new Utility();
        return view('master.service.detail', compact(['model','uti','category']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MServicesModels::findOrFail($id);

        $categories = DB::table('m_category as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $category = ServiceCategoryModels::where('service_id',$model->id)
                    ->pluck('category_id')
                    ->toArray();

        $documents = DB::table('m_documents as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $document = ServiceDocumentModels::where('service_id',$model->id)
                    ->pluck('document_id')
                    ->toArray();

        return view('master.service.create', compact('model','documents','document','categories','category'));
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
            'name'          => 'required|string|unique:m_services,name,'.$id.',id,is_deleted,0',
            // 'sort'          => 'required|numeric|unique:m_services,sort,'.$id.',id,is_deleted,0',
            'category_id.*'   => 'required|string',
            'document.*'    => 'required|string'
        ]);

        $model = MServicesModels::findOrFail($id);
        $userId = Auth::user()->id;
        
        $data = [
            'name'          => $request->name,
            'sort'          => $request->sort,
            'updated_by'    => $userId
        ];  
            
        $update = $model->update($data);
        if($update){

            $cek = DB::table('m_service_categories as a')
                    ->where('a.service_id', $model->id)
                    ->count();

            $cekDoc = DB::table('m_service_documents as a')
                    ->where('a.service_id', $model->id)
                    ->count();

                if ($cek > 0) {
                    
                    $delete = ServiceCategoryModels::where('service_id',$model->id)->delete();
                    if ($delete) {

                        // Save Category Service
                        foreach($request->category_id as $value)
                        {
                            $data = [
                                'service_id' => $model->id,
                                'category_id' => $value
                            ];
                            ServiceCategoryModels::create($data);
                        }
                    }
                }else{
                    // Save Category Service
                    foreach($request->category_id as $value)
                    {
                        $data = [
                            'service_id' => $model->id,
                            'category_id' => $value
                        ];
                        ServiceCategoryModels::create($data);
                    }
                }


                if ($cekDoc > 0) {
                    
                    $delete = ServiceDocumentModels::where('service_id',$model->id)->delete();
                    if ($delete) {

                        // Save Documents Service
                        foreach($request->document as $value)
                        {
                            $data = [
                                'service_id' => $model->id,
                                'document_id' => $value
                            ];
                            ServiceDocumentModels::create($data);
                        }
                    }
                }else{

                    foreach($request->document as $value)
                    {
                        $data = [
                            'service_id' => $model->id,
                            'document_id' => $value
                        ];
                        ServiceDocumentModels::create($data);
                    }
                }
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
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update m_services set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function dataTable()
    {
        $model = MServicesModels::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.service.action', [
                    'model' => $model,
                    'url_show'=> route('services.show', $model->id),
                    'url_edit'=> route('services.edit', $model->id),
                    'url_destroy'=> route('services.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }
}
