<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceDocumentModels;
use App\Models\MDocumentsModel;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class ServiceDocumentControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.service-document.index');
    }

    /**
     * Show the form for creating a new resource.
     *services
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documents = DB::table('m_documents as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $services = DB::table('m_services as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $document = NULL;

        $model = new ServiceDocumentModels();
        return view('master.service-document.create', compact('model','services','documents','document'));

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
            'document.*'       => 'required|string',
            'service_id'          => 'required|string'
        ]);

        foreach($request->document as $value)
        {
            $data = [
                'service_id' => $request->service_id,
                'document_id' => $value
            ];
            ServiceDocumentModels::create($data);
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
        $model = ServiceDocumentModels::findOrFail($id);

        $service = DB::table('m_service_documents as a')
                    ->join('m_services as b','b.id','=','a.service_id')
                    ->where('a.service_id', $model->service_id)
                    ->select('b.id','b.name as service')
                    ->first();

                    // dd($service);

        $uti = new Utility();
        return view('master.service-document.detail', compact(['model','uti','service']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ServiceDocumentModels::findOrFail($id);
        $documents = DB::table('m_documents as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $services = DB::table('m_services as a')
                    ->where('a.is_deleted', 0)
                    ->pluck('a.name','a.id')
                    ->all();

        $document = ServiceDocumentModels::where('service_id',$model->service_id)
                    ->pluck('document_id')
                    ->toArray();

        return view('master.service-document.create', compact('model','services','documents','document'));
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
            'document.*'       => 'required|string',
            'service_id'          => 'required|string'
        ]);
        $model = ServiceDocumentModels::findOrFail($id);
        if($request->document != null){
            
            $cek = DB::table('m_service_documents as a')
                    ->where('a.service_id', $model->service_id)
                    ->count();

                if ($cek > 0) {
                    
                    $delete = ServiceDocumentModels::where('service_id',$model->service_id)->delete();

                    if ($delete) {

                        foreach($request->document as $value)
                        {
                            $data = [
                                'service_id' => $request->service_id,
                                'document_id' => $value
                            ];
                            ServiceDocumentModels::create($data);
                        }
                    }
                }else{
                    foreach($request->document as $value)
                    {
                        $data = [
                            'service_id' => $request->service_id,
                            'document_id' => $value
                        ];
                        ServiceDocumentModels::create($data);
                    }
                }
        }else{
            $delete = ServiceDocumentModels::where('service_id',$model->service_id)->delete();
            
            $data = [
                'service_id' => $request->service_id,
                'document_id' => ''
            ];
            ServiceDocumentModels::create($data);
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
        $model = ServiceDocumentModels::findOrFail($id);
        $delete = ServiceDocumentModels::where('service_id',$model->service_id)->delete();
    }

    public function dataTable()
    {
        $model = DB::table('m_service_documents as a')
                ->join('m_documents as b','b.id','=','a.document_id')
                ->join('m_services as c','c.id','=','a.service_id')
                ->select('a.id','c.name as service','b.name as document')
                ->groupBy('service')
                ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.service-document.action', [
                    'model' => $model,
                    'url_show'=> route('service-document.show', $model->id),
                    'url_edit'=> route('service-document.edit', $model->id),
                    'url_destroy'=> route('service-document.destroy', $model->id)
                ]);
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by'])
            ->make(true);
    }

    public function documentRelated($id)
    {
        $model = DB::table('m_service_documents as a')
                ->join('m_documents as b','b.id','=','a.document_id')
                ->where('a.service_id', $id)
                ->select('a.id', 'b.name as document')
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
