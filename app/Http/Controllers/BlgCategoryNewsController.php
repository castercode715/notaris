<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlgCategoryNews; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class BlgCategoryNewsController extends Controller
{
    const MODULE_NAME = 'Category News';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.category_news.index');
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

        $model = new BlgCategoryNews();
        return view('master.category_news.create', compact('model'));
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
            'name'          => 'required|string'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $data = [
            'name'      => ucwords($request->name),
            'active'    => $active,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];
		

        BlgCategoryNews::create($data);
		
        //return redirect('master/category-news');
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

        $model = BlgCategoryNews::findOrFail($id);
        $uti = new Utility();
        return view('master.category_news.detail', compact(['model','uti']));
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

        $a = ['mst_news_category_detail'];
        $b = ['news_id'];


        if(!$this->isAllowedToUpdate($a, $b, $id))
            abort(400, 'Table has related.');

        $model = BlgCategoryNews::findOrFail($id);
        return view('master.category_news.create', compact('model'));
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
            'name'          => 'required|string',
            
        ]);

        $model = BlgCategoryNews::findOrFail($id);
        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;
		
		$data = [
                'name'      => ucwords($request->name),
                'active'    => $active,
                'updated_by'=> $userId
            ];  
			
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
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

		$deleted_date   = date('Y-m-d H:i:s');
		$deleted_by     = Auth::user()->id;

        DB::update("update mst_news_category set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }
	
	public function dataTable()
    {
        $model = BlgCategoryNews::query();
        $model->where('is_deleted','<>','1');
		
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.category_news.action', [
                    'model' => $model,
                    'url_show'=> route('category-news.show', $model->id),
                    'url_edit'=> route('category-news.edit', $model->id),
                    'url_destroy'=> route('category-news.destroy', $model->id)
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
