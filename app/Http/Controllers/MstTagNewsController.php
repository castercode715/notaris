<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstNewsTag;
use App\Models\MstNewsTagLang;
use App\Models\MstNewsTagDetail;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;


class MstTagNewsController extends Controller
{
    const MODULE_NAME = 'Tag';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.news_tag.index');
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

        $model = new MstNewsTag();
        
        $language = MstLanguage::all();

        return view('master.news_tag.create', compact(['model','language']));
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
            'description.*'  => 'required'
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

            if($model = MstNewsTag::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    $data = [
                        'tag_id'   => $model->id,
                        'code'          => $value,
                        'description'   => $request->description[$key]
                    ];
                    MstNewsTagLang::create($data);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/news-tag/'. base64_encode($model->id) )->with('success','Success');
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
        $model = MstNewsTag::findOrFail($id);
        $data = DB::table('mst_news_tag_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.tag_id',$id)
                    ->select('a.*','c.language')
                    ->get();
        return view('master.news_tag.detail', compact(['model','data']));
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

        $model = MstNewsTag::findOrFail($id);

        $data = DB::table('mst_news_tag_lang as a')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.tag_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        return view('master.news_tag.edit', compact(['model','data']));
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
            'description.*'  => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstNewsTag::findOrFail($id);
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
                    $lang = MstNewsTagLang::findOrFail($value);

                    $data = [
                        'code'   => $request->code[$key],
                        'description'   => $request->description[$key]
                    ];

                    $lang->update($data);
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/news-tag/'. base64_encode($model->id) )->with('success','Success');
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

        DB::update("update mst_news_tag set 
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

        $delete = DB::update("update mst_news_tag set 
            deleted_at='".$deleted_at."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/news-category')->with('success', 'Deleted');
        }
        else
            return redirect('master/news-category/'.base64_encode($id))->with('error', 'Failed');
    }
	
	public function dataTable()
    {
        $model = MstNewsTag::query();
        $model->join('mst_news_tag_lang','mst_news_tag_lang.tag_id','mst_news_tag.id');
        $model->select('mst_news_tag.id','mst_news_tag_lang.description');
        $model->where('mst_news_tag_lang.code',"IND");
        $model->where('mst_news_tag.is_deleted','0');
        $model->orderBy('mst_news_tag.created_at','desc');
		
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.news_tag.action', [
                    'model' => $model,
                    'url_show'=> route('news-tag.show', base64_encode($model->id) ),
                    'url_edit'=> route('news-tag.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('news-tag.destroy', base64_encode($model->id) )
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTableDetail($id)
    {
        $id = base64_decode($id);

        $model = DB::table('mst_news_tag_lang as a')
                    ->join('mst_language as b','a.code','b.code')
                    ->where('a.tag_id',$id)
                    ->select('a.description','b.language','a.id')
                    ->orderBy('a.code')
                    ->get();

        return DataTables::of($model)
                ->addIndexColumn()
                ->make(true);
    }
}
