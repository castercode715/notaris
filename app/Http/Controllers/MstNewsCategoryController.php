<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstNewsCategory; 
use App\Models\MstNewsCategoryLang; 
use App\Models\MstNewsCategoryDetail; 
use App\Models\MstLanguage; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstNewsCategoryController extends Controller
{
    const MODULE_NAME = 'Category News';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.news_category.index');
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

        $model = new MstNewsCategory();

        $language = MstLanguage::all();

        return view('master.news_category.create', compact(['model','language']));
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
            'code.*'  => 'required|distinct|min:1',
            'name.*'  => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            
            $data = [
                'active'        => $active,
                'created_by'    => $userId,
                'updated_by'    => $userId
            ];

            if($model = MstNewsCategory::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    $data = [
                        'category_id'   => $model->id,
                        'code'          => $value,
                        'name'          => $request->name[$key]
                    ];
                    MstNewsCategoryLang::create($data);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/news-category/'. base64_encode($model->id) )->with('success','Success');
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
        $model = MstNewsCategory::findOrFail($id);
        $data = DB::table('mst_news_category_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.category_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        $uti = new Utility();
        return view('master.news_category.detail', compact(['model','uti']));
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

        $model = MstNewsCategory::findOrFail($id);

        $data = DB::table('mst_news_category_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.category_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        return view('master.news_category.edit', compact(['model','data']));
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
            'code.*'  => 'required|distinct|min:1',
            'name.*'  => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstNewsCategory::findOrFail($id);
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            
            $data = [
                'active'        => $active,
                'created_by'    => $userId,
                'updated_by'    => $userId
            ];

            if($model->update($data))
            {
                foreach($request->id as $key => $value)
                {
                    $lang = MstNewsCategoryLang::findOrFail($value);

                    $data = [
                        'code'   => $request->code[$key],
                        'name'   => $request->name[$key]
                    ];

                    $lang->update($data);
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/news-category/'. base64_encode($model->id) )->with('success','Success');
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
		$deleted_date   = date('Y-m-d H:i:s');
		$deleted_by     = Auth::user()->id;

        DB::update("update mst_news_category set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);
        $deleted_at   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_news_category set 
            deleted_at='".$deleted_at."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/news-tag')->with('success', 'Deleted');
        }
        else
            return redirect('master/news-tag/'.base64_encode($id))->with('error', 'Failed');
    }
	
	public function dataTable()
    {
        $model = MstNewsCategory::query();
        $model->join('mst_news_category_lang','mst_news_category_lang.category_id','mst_news_category.id');
        $model->select('mst_news_category.id','mst_news_category_lang.name');
        $model->where('mst_news_category_lang.code',"IND");
        $model->where('mst_news_category.is_deleted','0');
        $model->orderBy('mst_news_category.created_at','desc');
		
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.news_category.action', [
                    'model' => $model,
                    'url_show'=> route('news-category.show', base64_encode($model->id) ),
                    'url_edit'=> route('news-category.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('news-category.destroy', base64_encode($model->id) )
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTableDetail($id)
    {
        $id = base64_decode($id);

        $model = DB::table('mst_news_category_lang as a')
                    ->join('mst_language as b','a.code','b.code')
                    ->where('a.category_id',$id)
                    ->select('a.name','b.language','a.id')
                    ->orderBy('a.code')
                    ->get();

        return DataTables::of($model)
                ->addIndexColumn()
                ->make(true);
    }
}
