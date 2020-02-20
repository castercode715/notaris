<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlgNews;
use App\Models\BlgCategoryNews;
use App\Models\MstNewsCategoryDetail;
use Illuminate\Support\Facades\Auth;
use Hash;
use DataTables;
use DB;
use App\Utility;

class BlgNewsController extends Controller
{
    const MODULE_NAME = 'Data News';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.news.index');
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

        $model = new BlgNews();
        $page = BlgCategoryNews::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('name', 'id')
                    ->all();
        return view('master.news.create', compact('model','page'));
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
            'title'     => 'required|string',
            'subtitle'  => 'required|string',
            'desc'      => 'required|string',
            'iframe'      => 'required|string',
            'category'    => 'required',
            'image'       => 'required|file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $image_filename = '';

        $image = $request->file('image');
        if($image != null)
        {
            $image_filename = time().$image->getClientOriginalName();
            $path = base_path().'/public/images/news/';
            $image->move($path, $image_filename);
        }



        $data = [
            'title'     => $request->title,
            'sub_title'  => $request->subtitle,
            'desc'      => $request->desc,
            'image'     => $image_filename,
            'iframe'    => $request->iframe,
            'active'    => $active,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];

        $model = BlgNews::create($data);
        if($model)
        {
            $category_name  = $request->category;
            foreach($category_name as $key => $value)
            {
                $attributes = [ 
                    'news_id'=>$model->id,
                    'category_id' => $value, 
                    'active' => '1',
                    'created_by' => $userId,
                    'updated_by' => $userId
                ];
                $attr = MstNewsCategoryDetail::create($attributes);
            }
            return redirect('master/news/'.$model->id)->with('success','News successfully created');
        }
        else
            Redirect::back()->withErrors(['error', 'Failed']);
        
        
            //return redirect('master/news/setposition/'.$model->id)->with('success','Success');
			
        
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

		$model = BlgNews::findOrFail($id);
        $uti = new Utility();
        return view('master.news.detail', compact(['model','uti']));
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


        $model = BlgNews::findOrFail($id);

        $countries = BlgCategoryNews::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('name', 'id')
                    ->all();

        $cat = MstNewsCategoryDetail::where('news_id',$id)
                    ->where('is_deleted','0')
                    ->pluck('category_id')
                    ->toArray();

        return view('master.news.update', compact(['model','countries', 'cat']));
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
            'title'     => 'required|string',
            'sub_title'  => 'required|string',
            'desc'      => 'required|string',
            'iframe'      => 'required|string',
            'category'    => 'required',
            'image'       => 'required|file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        $model = BlgNews::findOrFail($id);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0; 

        $cat = $request->category;
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;
        if($cat != null){
            DB::update("update mst_news_category_detail set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where news_id = ".$id."");
        }

        foreach($cat as $key => $value)
        {
            $attributes = [ 
                'news_id'=>$id,
                'category_id' => $value, 
                'active' => '1',
                'created_by' => $userId,
                'updated_by' => $userId
            ];

            $attr = MstNewsCategoryDetail::create($attributes);
        }




        if($request->file('image'))
        {
            $image = $request->file('image');
            $filename = time().$image->getClientOriginalName();
            $path = base_path().'/public/images/news/';
            $image->move($path, $filename);

            $data = [
                'title'     => $request->title,
				'sub_title'  => $request->sub_title,
				'desc'      => $request->desc,
				'image'     => $filename,
				'iframe'    => $request->iframe,
				'active'    => $active,
				'created_by'=> $userId,
				'updated_by'=> $userId
            ];

            if($model->image != null)
                unlink(base_path().'/public/images/news/'.$model->image);            
        }
        else
        {
            $data = [
                'title'     => $request->title,
				'sub_title'  => $request->sub_title,
				'desc'      => $request->desc,
				'iframe'    => $request->iframe,
				'active'    => $active,
				'created_by'=> $userId,
				'updated_by'=> $userId
            ];   
        }

        $model->update($data);
        return redirect('master/news');
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
		
        DB::update("update mst_news set 
            deleted_at='".$deleted_date."',
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

        $delete = DB::update("update mst_news set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
            return redirect('master/news')->with('success', 'Deleted');
        else
            return redirect('master/news')->with('error', 'Failed');
    }
	
	public function dataTable()
    {
        $model = BlgNews::query();
        $model->where('is_deleted','<>','1');
		
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.news.action', [
                    'model' => $model,
                    'url_show'=> route('news.show', $model->id),
                    'url_edit'=> route('news.edit', $model->id),
                    'url_destroy'=> route('news.destroy', $model->id)
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
