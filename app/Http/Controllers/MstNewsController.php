<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstNews;
use App\Models\MstNewsLang;
use App\Models\MstNewsCategory;
use App\Models\MstNewsCategoryLang;
use App\Models\MstNewsCategoryDetail;
use App\Models\MstNewsTag;
use App\Models\MstNewsTagLang;
use App\Models\MstNewsTagDetail;
use App\Models\MstLanguage;
use App\Models\MstNewsComment;
use App\Models\VNews;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class MstNewsController extends Controller
{
    const MODULE_NAME = 'Data News';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

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

        $model = new MstNews();

        $category = MstNewsCategory::join('mst_news_category_lang','mst_news_category_lang.category_id','mst_news_category.id')
                    ->where('mst_news_category_lang.code',"IND")
                    ->where('mst_news_category.active','1')
                    ->where('mst_news_category.is_deleted','0')
                    ->pluck('mst_news_category_lang.name','mst_news_category_lang.category_id')
                    ->all();

        $tag = MstNewsTag::join('mst_news_tag_lang','mst_news_tag_lang.tag_id','mst_news_tag.id')
                    ->where('mst_news_tag_lang.code',"IND")
                    ->where('mst_news_tag.active','1')
                    ->where('mst_news_tag.is_deleted','0')
                    ->pluck('mst_news_tag_lang.description','mst_news_tag_lang.tag_id')
                    ->all();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.news.create', compact(['model','language','category','tag']));
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
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'desc' => 'required|string',
            'image' => 'file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;
            
            $data = [
                'active' => 0,
                'created_by'=> $userId,
                'updated_by'=> $userId
            ];

            if($model = MstNews::create($data))
            {
                /*
                * Add News Language
                */
                $data = [
                    'news_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'sub_title' => $request->sub_title,
                    'description' => $request->desc
                ];

                if($request->iframe != '')
                    $data = array_merge($data, ['iframe' => $request->iframe]);

                if($image = $request->file('image'))
                {
                    $image_filename = 'IND'.time().rand(10,99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/news/';
                    $image->move($path, $image_filename);
                    $data = array_merge($data, ['image'=>$image_filename]);
                }
                MstNewsLang::create($data);
                /*
                * Add Category
                */
                foreach($request->category as $value)
                {
                    $data = [
                        'news_id' => $model->id,
                        'category_id' => $value
                    ];
                    MstNewsCategoryDetail::create($data);
                }
                /*
                * Add Tag
                */
                foreach($request->tag as $value)
                {
                    $data = [
                        'news_id' => $model->id,
                        'tag_id' => $value
                    ];
                    MstNewstagDetail::create($data);
                } 
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');                   
                return redirect('master/news/'. base64_encode($model->id))->with('success', 'Created successfully');
            }
            else
                return redirect('master/news')->with('error', 'Failed');
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstNewsLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.news.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'sub_title'     => 'required|string',
            'description'      => 'required|string',
            'news_id'   => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'news_id' => $request->news_id,
                'code' => $request->code,
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'description' => $description
            ];

            if($request->iframe != '')
                $data = array_merge($data, ['iframe'=>$request->iframe]);

            if($image = $request->file('image'))
            {
                $image_filename = $request->code.time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/news/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
            }

            if($model = MstNewsLang::create($data))
            {
                $news = MstNews::findOrFail($model->news_id);
                if($news->isComplete())
                {
                    $news->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/news/'. base64_encode($model->news_id))->with('success', 'News created successfully');
            }
            else
                return redirect('master/news')->with('error', 'Failed');
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

        $news = MstNews::findOrFail($id);
        /*
        * Detail
        */
        $model = DB::table('mst_news as p')
                    ->join('mst_news_lang as pl','p.id','pl.news_id')
                    ->where('p.id', $id)
                    ->where('pl.code', 'IND')
                    ->select(
                        'pl.id as news_lang_id',
                        'pl.title',
                        'pl.sub_title',
                        'pl.description',
                        'pl.image',
                        'pl.iframe',
                        'p.*'
                    )
                    ->first();

        $category = DB::table('mst_news_category_detail as a')
                    ->join('mst_news_category as b','b.id','a.category_id')
                    ->join('mst_news_category_lang as c','c.category_id','b.id')
                    ->select('c.name')
                    ->where('a.news_id',$id)
                    ->where('c.code',"IND")
                    ->get();

        $tag = DB::table('mst_news_tag_detail as a')
                    ->join('mst_news_tag as b','b.id','a.tag_id')
                    ->join('mst_news_tag_lang as c','c.tag_id','b.id')
                    ->select('c.description as name')
                    ->where('a.news_id',$id)
                    ->where('c.code',"IND")
                    ->get();
        /*
        * Comment
        */
        $comment = new MstNewsComment;
        $commentList = DB::table('mst_news_comment as nc')
                    ->join('mst_investor as i','nc.investor_id','i.id')
                    ->select('i.full_name as investor','i.photo','nc.comment','nc.created_at','nc.id','nc.is_visible')
                    ->where('nc.is_deleted',0)
                    ->where('nc.parent',null)
                    ->orderBy('nc.created_at','desc')
                    ->get();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.news.detail', compact([
            'news', 
            'model', 
            'language', 
            'category', 
            'tag', 
            'comment', 
            'commentList', 
            'uti'
        ]));
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

        $model = MstNews::findOrFail($id);

        $category = MstNewsCategoryDetail::where('news_id',$id)
                    ->pluck('category_id')
                    ->toArray();

        $tag = MstNewsTagDetail::where('news_id',$id)
                    ->pluck('tag_id')
                    ->toArray();

        $categoryList = MstNewsCategory::join('mst_news_category_lang','mst_news_category_lang.category_id','mst_news_category.id')
                    ->where('mst_news_category_lang.code',"IND")
                    ->where('mst_news_category.active','1')
                    ->where('mst_news_category.is_deleted','0')
                    ->pluck('mst_news_category_lang.name','mst_news_category_lang.category_id')
                    ->all();

        $tagList = MstNewsTag::join('mst_news_tag_lang','mst_news_tag_lang.tag_id','mst_news_tag.id')
                    ->where('mst_news_tag_lang.code',"IND")
                    ->where('mst_news_tag.active','1')
                    ->where('mst_news_tag.is_deleted','0')
                    ->pluck('mst_news_tag_lang.description','mst_news_tag_lang.tag_id')
                    ->all();

        return view('master.news.update', compact([
            'model',
            'category',
            'tag',
            'categoryList',
            'tagList'
        ])); 
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
            'category'   => 'required',
            'tag'   => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstNews::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'active'  => $request->active,
                'updated_by'  => $userId
            ];
            
            if($model->update($data))
            {
                /* delete category jika ada */
                $oldCategories = DB::table('mst_news_category_detail')->where('news_id',$id)->get();
                foreach($oldCategories as $oldCategory)
                {
                    if(!in_array($oldCategory->category_id, $request->category))
                    {
                        DB::table('mst_news_category_detail')->where('news_id',$id)
                            ->where('category_id',$oldCategory->category_id)
                            ->delete();
                    }
                }
                /* insert new category */
                foreach($request->category as $category)
                {
                    $cat = MstNewsCategoryDetail::where('news_id',$id)->where('category_id', $category)->count();
                    if($cat == 0)
                    {
                        $newCategory = [
                            'news_id' => $id,
                            'category_id' => $category
                        ];
                        MstNewsCategoryDetail::create($newCategory);
                    }
                }

                /* delete tag jika ada */
                $oldTags = DB::table('mst_news_tag_detail')->where('news_id',$id)->get();
                foreach($oldTags as $oldTag)
                {
                    if(!in_array($oldTag->tag_id, $request->tag))
                    {
                        DB::table('mst_news_tag_detail')->where('news_id',$id)
                            ->where('tag_id',$oldTag->tag_id)
                            ->delete();
                    }
                }
                /* insert new tag */
                foreach($request->tag as $tag)
                {
                    $t = MstNewsTagDetail::where('news_id',$id)->where('tag_id', $tag)->count();
                    if($t == 0)
                    {
                        $newTag = [
                            'news_id' => $id,
                            'tag_id' => $tag
                        ];
                        MstNewsTagDetail::create($newTag);
                    }
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/news/'. base64_encode($model->id))->with('success', 'News updated successfully');
            }
            else
                return redirect('master/news')->with('error', 'Failed');
        }
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstNewsLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.news.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'         => 'required|string',
            'sub_title'     => 'required|string',
            'description'   => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstNewsLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'description' => $description,
                'iframe' => $request->iframe
            ];

            $deleteImage = '';
            if($model->image)
            {
                if($request->img == '' || $request->img == null)
                {
                    $deleteImage = base_path().'/public/images/news/'.$model->image;
                    $data = array_merge($data, ['image'=>null]);
                }
            }

            if($image = $request->file('image'))
            {
                $image_filename = $model->code.time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/news/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
                // delete old image
                if($model->image != null)
                    $deleteImage = base_path().'/public/images/news/'.$model->image;
            }

            if($deleteImage)
                unlink($deleteImage);

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/news/'. base64_encode($model->news_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/news')->with('error', 'Failed');
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
        
        DB::update("update mst_news set 
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

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_news set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/news')->with('success', 'Deleted');
        }
        else
            return redirect('master/news')->with('error', 'Failed');
    }

    public function dataTable()
    {
        /*$model = MstNews::query();
        $model->join('mst_news_lang','mst_news_lang.news_id','=','mst_news.id');
        $model->select(
            'mst_news.id',
            'mst_news.view_count',
            'mst_news.active',
            'mst_news_lang.title as title',
            'mst_news.created_at',
            'mst_news.created_by'
        );
        $model->where('mst_news_lang.code',"IND");
        $model->where('mst_news.is_deleted','0');
        $model->orderBy('mst_news.created_at','desc');
        $model->get();*/

        /*$model = DB::table('mst_news as n')
                ->join('mst_news_lang as nl','nl.news_id','n.id')
                ->select(
                    'n.id',
                    'n.view_count',
                    'n.active',
                    'n.created_at',
                    'n.created_by',
                    'nl.title'
                )
                ->where('nl.code',"IND")
                ->where('n.is_deleted',0)
                ->orderBy('n.created_at','desc')
                ->get();*/
        $model = VNews::query();
        $model->orderBy('created_at','desc');

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.news.action', [
                    'model' => $model,
                    'url_show'=> route('news.show', base64_encode($model->id) ),
                    'url_edit'=> route('news.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('news.destroy', base64_encode($model->id) )
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d M Y H:i', strtotime($model->created_at)).' WIB';
            })
            ->editColumn('created_by', function($model){
                $uti = new Utility;
                return $uti->getUser($model->created_by);
            })
            ->editColumn('active', function($model){
                return $model->active == 1 ? "<span class='label label-primary'>Active</span>":"<span class='label label-danger'>Inactive</span>";
            })
            ->addIndexColumn()
            ->rawColumns(['action','created_by','created_at','active'])
            ->make(true);
    }

    public function commentChild($id)
    {
        $rawdata = DB::table('mst_news_comment as nc')
                ->join('mst_investor as i','nc.investor_id','i.id')
                ->select('i.full_name as investor','i.photo','nc.comment','nc.created_at','nc.id','nc.is_visible')
                ->where('nc.is_deleted', 0)
                ->where('nc.parent', $id)
                ->orderBy('nc.created_at','desc')
                ->get();

        $data = [];
        foreach($rawdata as $value)
        {
            $total = DB::table('mst_news_comment')
                ->where('parent',$value->id)
                ->count();

            $data[] = [
                'image' => $value->photo,
                'fullname' => $value->investor,
                'id' => $value->id,
                'createdAt' => $value->created_at,
                'comment' => $value->comment,
                'isVisible' => $value->is_visible,
                'childTotal' => $total
            ];
        }
        echo json_encode($data);
    }

    public function approveComment($id)
    {
        MstNewsComment::findOrFail($id)->update(['is_visible'=>1]);
    }

    public function rejectComment($id)
    {
        MstNewsComment::findOrFail($id)->update(['is_visible'=>0]);
    }
}
