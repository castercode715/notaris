<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstBanner;
use App\Models\MstBannerLang;
use App\Models\MstBannerDetail;
use App\Models\MstPage;
use App\Models\MstLanguage;
use App\Models\MstPosition;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstBannerController extends Controller
{
    const MODULE_NAME = 'Banner';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.banner.index');
    }

    /* ------------------------------------------
     CREATE / STORE
     ------------------------------------------ */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $model = new MstBanner();

        $language = MstLanguage::where('code','IND')->first();

        $position = MstPosition::where('is_deleted', 0)
                        ->where('active', 1)
                        ->pluck('description', 'id')
                        ->all();

        return view('master.banner.create', compact(['model','language','position']));
    }

    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $rules = [
            'title' => 'required|string',          
            'sub_title' => 'required|string',          
            'link' => 'required|string',          
            'code' => 'required|string',          
            'description' => 'required|string',
            'image' => 'required_without:iframe',  
            'iframe' => 'required_without:image',
            'position_id.*' => 'required|distinct|min:1',
            'order.*' => 'required',
        ];

        $messages = [
            'image.required_if'   => 'Image field is required when iFrame is empty.',
            'iframe.required_if'   => 'iFrame field is required when Image is empty.'
        ];

        $validate = $this->validate($request, $rules, $messages);

        if($validate)
        {
            $userId = Auth::user()->id;

            $data = [
                'link' => $request->link,
                'active' => 0,
                'created_by' => $userId
            ];

            if($model = MstBanner::create($data))
            {
                $data = [
                    'banner_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'sub_title' => $request->sub_title,
                    'description' => $request->description
                ];

                if($image = $request->file('image'))
                {
                    $filename = time().rand(10, 99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/banner/';
                    $image->move($path, $filename);
                    $data = array_merge($data, ['image'=>$filename]);
                }

                if($request->iframe != null)
                    $data = array_merge($data, ['iframe'=>$request->iframe]);

                if(MstBannerLang::create($data))
                {
                    if($request->position_id)
                    {
                        $position  = $request->position_id;
                        $order = $request->order;

                        foreach($position as $key => $value)
                        {
                            $data = [ 
                                'banner_id' => $model->id,
                                'position_id' => $value, 
                                'order' => $order[$key]
                            ];
                            MstBannerDetail::create($data);
                        }
                    }
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/banner/'. base64_encode($model->id))->with('success','Success');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstBannerLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.banner.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'sub_title'     => 'required|string',
            'description'      => 'required|string',
            'image' => 'required_without:iframe',  
            'iframe' => 'required_without:image'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'banner_id' => $request->banner_id,
                'code' => $request->code,
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'description' => $description
            ];

            if($request->iframe != null)
                $data = array_merge($data, ['iframe'=>$request->iframe]);

            if($image = $request->file('image'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/banner/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
            }

            if($model = MstBannerLang::create($data))
            {
                $banner = MstBanner::findOrFail($model->banner_id);

                if($banner->isComplete())
                {
                    $banner->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/banner/'. base64_encode($model->banner_id))->with('success', 'Banner created successfully');
            }
            else
                return redirect('master/banner')->with('error', 'Failed');
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

        $banner = MstBanner::findOrFail($id);

        $model = DB::table('mst_banner as p')
                    ->join('mst_banner_lang as pl','p.id','pl.banner_id')
                    ->where('p.id', $id)
                    ->where('pl.code', 'IND')
                    ->select(
                        'pl.id as banner_lang_id',
                        'pl.title',
                        'pl.sub_title',
                        'pl.description',
                        'pl.image',
                        'pl.iframe',
                        'p.*'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $position = DB::table('mst_banner_detail as bd')
                    ->join('mst_position as p','bd.position_id','p.id')
                    ->where('bd.banner_id', $id)
                    ->orderBy('bd.position_id', 'desc')
                    ->get();

        $uti = new Utility();

        return view('master.banner.detail', compact([
            'banner', 
            'model', 
            'position', 
            'language', 
            'uti'
        ]));
    }

    public function setposition($id)
    {
        $model = MstBanner::findOrFail($id);
        $page = MstPage::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('name', 'id')
                    ->all();
        $bannerdetail = DB::table('mst_banner_detail as a')
                        ->join('mst_banner as b','a.banner_id','=','b.id')
                        ->join('mst_position as c','a.position_id','=','c.id')
                        ->join('mst_page as d','c.page_id','=','d.id')
                        ->where('a.banner_id',$id)
                        ->select('d.name as page','c.name as position','a.*')
                        ->get();
        return view('master.banner.setposition', compact(['model','page','bannerdetail']));
    }

    public function set(Request $request)
    {
        $this->validate($request, [
            'page'     => 'required',
            'position' => 'required'
        ]);

        $data = [
            'banner_id'     => $request->banner,
            'position_id'   => $request->position,
            'order'         => $request->order
        ];

        $model = MstBannerDetail::create($data);
        return $model;
    }

    public function deleteposition($b, $p)
    {
        DB::table('mst_banner_detail')
            ->where('banner_id',$b)
            ->where('position_id',$p)
            ->delete();
    }

    /* ------------------------------------------
     EDIT / UPDATE
     ------------------------------------------ */
    public function edit($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstBanner::findOrFail($id);

        $position = DB::table('mst_banner_detail as bd')
                    ->join('mst_position as p','bd.position_id','p.id')
                    ->where('bd.banner_id', $id)
                    ->select('p.description','bd.order','bd.position_id','bd.banner_id')
                    ->get();

        return view('master.banner.update', compact(['model','position'])); 
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
            'link' => 'required|string',
            'position_id.*' => 'required|distinct|min:1',
            'order.*' => 'required',
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstBanner::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'link' => $request->link,
                'updated_by' => $userId,
                'updated_at' => $date
            ];

            if($request->active)
                $data =array_merge($data, ['active'=>$request->active]);

            if($model->update($data))
            {
                /*
                * Delete position yang dihapus di form update
                */
                if(is_array($request->position))
                {
                    $existeds = DB::table('mst_banner_detail')
                                ->where('banner_id', $model->id)
                                ->get();

                    foreach($existeds as $existed)
                    {
                        if( !in_array($existed->position_id, $request->position) )
                        {
                            MstBannerDetail::where('banner_id', $model->id)
                                            ->where('position_id', $existed->position_id)
                                            ->delete();
                        }
                    }
                }
                else
                {
                    DB::table('mst_banner_detail')->where('banner_id', $id)->delete();
                }
                /*
                * insert position baru
                */
                if( $request->position_id != null )
                {
                    foreach($request->position_id as $key => $pos)
                    {
                        $data = [
                            'banner_id' => $model->id,
                            'position_id' => $pos,
                            'order' => $request->order[$key]
                        ];
                        
                        MstBannerDetail::create($data);
                    }
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/banner/'. base64_encode($model->id))->with('success','Success');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }

    public function editNew($id)
    {
        $id = base64_decode($id);

        $model = MstBannerLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.banner.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'sub_title'     => 'required|string',
            'description'   => 'required',
            'image' => 'required_if:flag,I',  
            'iframe' => 'required_if:flag,V'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstBannerLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'description' => $description
            ];

            if($request->flag == 'V')
            {
                if($request->iframe != '')
                {
                    $data = array_merge($data, ['iframe' => $request->iframe]);
                    
                    if($model->image)
                    {
                        $data = array_merge($data, ['image' => null]);
                        unlink(base_path().'/public/images/banner/'.$model->image);
                    }
                }
            }

            if($request->flag == 'I')
            {
                if($image = $request->file('image'))
                {
                    $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/banner/';
                    $image->move($path, $image_filename);
                    $data = array_merge($data, ['image'=>$image_filename]);
                    
                    if($model->iframe)
                    {
                        $data = array_merge($data, ['iframe' => null]);
                    }
                }
            }

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/banner/'. base64_encode($model->banner_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/banner')->with('error', 'Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_banner set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/banner')->with('success', 'Deleted');
        }
        else
            return redirect('master/banner/show/'. base64_encode($id) )->with('error', 'Failed');
    }

    public function destroy($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_banner set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = DB::table('mst_banner as b')
                ->join('mst_banner_lang as bl','b.id','bl.banner_id')
                ->where('b.is_deleted', 0)
                ->where('bl.code', "IND")
                ->select('b.id','bl.title','b.created_by','b.created_at')
                ->orderBy('b.created_at', 'desc')
                ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.banner.action', [
                    'model' => $model,
                    'url_show'=> route('banner.show', base64_encode($model->id) ),
                    'url_edit'=> route('banner.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('banner.destroy', base64_encode($model->id) )
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
            ->rawColumns(['action', 'active', 'created_at', 'created_by'])
            ->make(true);
    }

    public function positionDataTable($id)
    {
        $model = DB::table('mst_banner_detail as a')
                ->join('mst_position as b','a.position_id','=','b.id')
                ->join('mst_page as c','b.page_id','=','c.id')
                ->where('a.banner_id',$id)
                ->select('c.name as page','b.name as position','a.*')
                ->get();

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.banner.action-position', [
                    'model' => $model,
                    'url_destroy' => route('banner.deletepos', ['b'=>$model->banner_id,'p'=>$model->position_id])
                    // 'url_destroy' => route('banner.delete', $model->position_id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action','page'])
            ->make(true);
    }

    public function getPosition(Request $request)
    {
        $data = DB::table('mst_position')
                ->where('is_deleted','0')
                ->where('active','1')
                ->get();
        
        $return = "<option value=''>- Select -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->id."'>".$row->description."</option>";
        }
        echo $return;
    }
}
