<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstTermsConds;
use App\Models\MstTermsCondsLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstTermsCondsController extends Controller
{
    const MODULE_NAME = 'Term And Condition';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.terms_conds.index');
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

        $lang = DB::table('mst_language as a')
                ->select('a.*')
                ->get();

        $model = new MstTermsConds();
        return view('master.terms_conds.create', compact('model','lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title.*'     => 'required|string',
            'desc.*'      => 'required|string',
            'view'      => 'required|string',
            'code.*'      => 'required|string',
            'sort'      => 'required|unique:mst_terms_conds,sort,1,is_deleted',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;
        $titles = $request->title;
        $desc = $request->desc;
        $sort = $request->sort;
        $view = $request->view;
        $code = $request->code;

        $data_trm = [
            'view' => $view,
            'sort' => $sort,
            'active' => $active,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];
        // dd($code);
        $save = MstTermsConds::create($data_trm);

        if($save){
            foreach($titles as $key => $title){
                $data = [
                    'terms_conds_id' => $save->id,
                    'code' => $code[$key],
                    'title'=> $title,
                    'description'=> $desc[$key],
                ];
                
                MstTermsCondsLang::create($data);
            }

            return redirect('master/terms-conds/'.base64_encode($save->id))->with('success', 'Success');
        }else{
            return redirect('master/terms-conds/create')->with('error', 'Failed');
        }

        
        // die();

        // $model = MstTermsConds::create($data);
        // if($model)
        //     return redirect('master/terms-conds/'.$model->id)->with('success', 'Success');
        // else
        //     return redirect('master/terms-conds/create')->with('error', 'Failed');
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
        $model = MstTermsConds::findOrFail($id);
        $uti = new Utility();
        return view('master.terms_conds.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if(!$this->checkAccess(self::MODULE_NAME, 'U'))
        //     abort(401, 'Unauthorized action.');

        // $a = ['mst_asset'];
        // $b = ['terms_conds_id'];


        // if(!$this->isAllowedToUpdate($a, $b, $id))
        //     abort(401, 'Table has related.');

        $model = MstTermsConds::findOrFail($id);
        $uti = new Utility();

     

        // dd($model);
        return view('master.terms_conds.index', compact(['model','uti'])); 
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
        $id = base64_decode($id);

        $iid = DB::table('mst_terms_conds_lang as a')
                ->select('a.*')
                ->where('a.id','=',$id)
                // ->pluck('a.title')
                ->first();
        $a = $iid->terms_conds_id;

        $this->validate($request, [
            'title'     => 'required|string',
            'description'      => 'required|string',
            'view'      => 'required|string',
            'sort'      => 'required|string|unique:mst_terms_conds,sort,'.$a.',id,is_deleted,0',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'title'     => $request->title,
            'description'      => $request->description
        ];

        $model = MstTermsCondsLang::findOrFail($id);

        // dd($data);
        $model->update($data);
        if($model){

            $id_trm = DB::table('mst_terms_conds_lang as a')
                ->select('a.*')
                ->where('a.id','=',$model->id)
                // ->pluck('a.title')
                ->first();
            $r = $id_trm->terms_conds_id;

             $data_trm = [
                'view'     => $request->view,
                'sort'      => $request->sort
             ];

             $up = MstTermsConds::findOrFail($r);
             $up->update($data_trm);          

            return redirect('master/terms-conds/edit_detail/'.base64_encode($model->id))->with('success', 'Success');
        }else{
            Redirect::back()->withErrors(['error', 'Failed']);
        }
            // return redirect('master/help')->with('error', 'Failed');
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
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_terms_conds set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_terms_conds set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
            return redirect('master/terms-conds')->with('success', 'Deleted');
        else
            Redirect::back()->withErrors(['error', 'Failed']);
            // return redirect('master/terms-conds')->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = MstTermsConds::query();
        $model->where('is_deleted','<>','1');

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.terms_conds.action', [
                    'model' => $model,
                    'url_show'=> route('terms-conds.show', base64_encode($model->id)),
                    'url_destroy'=> route('terms-conds.destroy', base64_encode($model->id))
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('title', function($model){

                $row = DB::table('mst_terms_conds_lang as a')
                ->select('a.title','a.code','a.terms_conds_id')
                ->where('a.code','=','IND')
                ->where('a.terms_conds_id','=',$model->id)
                // ->pluck('a.title')
                ->first();

                return $row->title;
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'title', 'created_by'])
            ->make(true);
    }

    public function dataTableLang($id)
    {
         $model = DB::table('mst_terms_conds as a')
                    ->join('mst_terms_conds_lang as b','a.id','b.terms_conds_id')
                    ->join('mst_language as c','b.code','c.code')
                    ->where('a.is_deleted','0')
                    ->where('b.terms_conds_id',$id)
                    ->select('a.id','b.title','b.id as no','b.description','c.language','a.created_at','a.created_by')
                    ->get();
        

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.terms_conds.action1', [
                    'model' => $model,
                    'url_show'=> route('terms-conds.detail', base64_encode($model->no)),
                    'url_edit'=> route('terms-conds.edit_detail', base64_encode($model->no))
                    
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })

           ->editColumn('language', function($model){
                return ucwords(strtolower($model->language));
            })
          
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'language', 'create_at', 'created_by'])
            ->make(true);
    }

    public function detail($id)
    {
        $id = base64_decode($id);
        $row = DB::table('mst_terms_conds_lang as a')
                ->join('mst_terms_conds as c', 'c.id', 'a.terms_conds_id')
                ->join('mst_language as b', 'b.code', 'a.code')
                ->select('c.*','a.*', 'b.language' )
                ->where('a.id','=',$id)
                ->first();
          $uti = new Utility();
        return view('master.terms_conds.detail-lang', compact(['row','uti']));
    }

    public function edit_detail($id)
    {
        $id = base64_decode($id);
        $model = DB::table('mst_terms_conds_lang as a')
                ->join('mst_terms_conds as c', 'c.id', 'a.terms_conds_id')
                ->join('mst_language as b', 'b.code', 'a.code')
                ->select('c.*','a.*', 'b.language' )
                ->where('a.id','=',$id)
                ->first();
          $uti = new Utility();
        return view('master.terms_conds.update', compact(['model','uti']));
    }
}
