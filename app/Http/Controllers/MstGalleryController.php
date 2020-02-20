<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstGallery;
use App\Models\MstGalleryPhoto;
use App\Models\MstGalleryLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstGalleryController extends Controller
{
    const MODULE_NAME = 'Data Gallery';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.gallery.index');
    }

    /* ------------------------------------------
     CREATE / STORE
     ------------------------------------------ */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');
        
        $model = new MstGallery();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.gallery.create', compact(['model','language']));
    }

    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $rules = [
            'title' => 'required|string',          
            'desc' => 'required|string',          
            'sort' => 'required|string|max:1|unique:mst_gallery,sort,1,is_deleted',          
            'flag' => 'required',
            'featured_img' => 'required_if:flag,I',
            'featured_iframe' => 'required_if:flag,V',
            'images.*' => 'required_if:flag,I',  
            'iframe.*' => 'required_if:flag,V',
        ];

        $messages = [
            'featured_img.required_if'   => 'Featured Image field is required when Flag is Image.',
            'featured_iframe.required_if'   => 'Featured iFrame field is required when Flag is iFrame.'
        ];

        $validate = $this->validate($request, $rules, $messages);

        if($validate)
        {
            $month = date('Y-m');
            $userId = Auth::user()->id;
            
            $data = [
                'sort' => $request->sort,
                'flag' => $request->flag,
                'active' => 0,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

            if($model = MstGallery::create($data))
            {
                $data = [
                    'gallery_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'description' => $request->desc
                ];

                if($lang = MstGalleryLang::create($data))
                {
                    if($model->flag == 'I')
                    {
                        $path = base_path().'/public/images/gallery/';
                        /*
                        * Upload featured image
                        */
                        $fimages = $request->file('featured_img');
                        $fimage_filename = 'F'.time().rand(10,99).'_'.$fimages->getClientOriginalName();
                        $fimages->move($path, $fimage_filename);
                        $data = [
                            'gallery_id' => $model->id,
                            'active' => 1,
                            'created_by' => $userId,
                            'updated_by' => $userId,
                            'photo' => $fimage_filename,
                            'featured' => 1
                        ];
                        MstGalleryPhoto::create($data);
                        /*
                        * Upload images
                        */
                        $images = $request->file('images');
                        foreach($images as $key => $image)
                        {
                            $img_name = 'I'.time().rand(10,99).'_'.$image->getClientOriginalName();
                            $image->move($path, $img_name);
                            $data = [
                                'gallery_id' => $model->id,
                                'active' => 1,
                                'created_by' => $userId,
                                'updated_by' => $userId,
                                'photo' => $img_name,
                                'featured' => 0
                            ];
                            MstGalleryPhoto::create($data);
                        }
                    }
                    elseif($model->flag == 'V')
                    {
                        /*
                        * Set featured iframe
                        */
                        $data = [
                            'gallery_id' => $model->id,
                            'active' => 1,
                            'created_by' => $userId,
                            'updated_by' => $userId,
                            'iframe' => $request->featured_iframe,
                            'featured' => 1
                        ];
                        MstGalleryPhoto::create($data);
                        /*
                        * Create iframe
                        */
                        $iframes = $request->iframes;
                        foreach($iframes as $key => $iframe)
                        {
                            $data = [
                                'gallery_id' => $model->id,
                                'active' => 1,
                                'created_by' => $userId,
                                'updated_by' => $userId,
                                'iframe' => $iframe,
                                'featured' => 0
                            ];
                            MstGalleryPhoto::create($data);
                        }
                    }
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/gallery/'. base64_encode($model->id) )->with('success', 'Gallery created successfully');
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

        $model = new MstGalleryLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.gallery.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'description'  => 'required|string',
            'gallery_id'   => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'gallery_id' => $request->gallery_id,
                'code' => $request->code,
                'title' => $request->title,
                'description' => $description
            ];

            if($model = MstGalleryLang::create($data))
            {
                $gallery = MstGallery::findOrFail($model->gallery_id);
                if($gallery->isComplete())
                {
                    $gallery->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/gallery/'. base64_encode($model->gallery_id))->with('success', 'gallery created successfully');
            }
            else
                return redirect('master/gallery')->with('error', 'Failed');
        }
    }

    /* ------------------------------------------
     SHOW
     ------------------------------------------ */
    public function show($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $gallery = MstGallery::findOrFail($id);

        $model = DB::table('mst_gallery as g')
                    ->join('mst_gallery_lang as gl','g.id','gl.gallery_id')
                    ->where('g.id', $id)
                    ->where('gl.code', 'IND')
                    ->select(
                        'gl.id as gallery_lang_id',
                        'gl.title',
                        'gl.description',
                        'g.id',
                        'g.created_by',
                        'g.updated_by',
                        'g.created_at',
                        'g.updated_at',
                        'g.flag',
                        'g.active',
                        'g.sort'
                    )
                    ->first();

        $featured = '';
        $attachments = [];
        if($gallery->flag == 'I')
        {
            $attachments = DB::table('mst_gallery as g')
                        ->join('mst_gallery_photo as gp','g.id','gp.gallery_id')
                        ->where('g.id', $id)
                        ->where('gp.featured', '0')
                        ->select('gp.photo','gp.id')
                        ->get();

            $featured = DB::table('mst_gallery as g')
                        ->join('mst_gallery_photo as gp','g.id','gp.gallery_id')
                        ->where('g.id', $id)
                        ->where('gp.featured', '1')
                        ->select('gp.photo')
                        ->first();
        }
        else
        {
            $attachments = DB::table('mst_gallery as g')
                        ->join('mst_gallery_photo as gp','g.id','gp.gallery_id')
                        ->where('g.id', $id)
                        ->where('gp.featured', '0')
                        ->select('gp.iframe','gp.id')
                        ->get(); 

            $featured = DB::table('mst_gallery as g')
                        ->join('mst_gallery_photo as gp','g.id','gp.gallery_id')
                        ->where('g.id', $id)
                        ->where('gp.featured', '1')
                        ->select('gp.iframe')
                        ->first();  
        }

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.gallery.detail', compact([
            'gallery', 
            'model', 
            'featured', 
            'attachments', 
            'language', 
            'uti'
        ]));
    }

    public function iframeDetail($id)
    {
        $id = base64_decode($id);
        $model = MstGalleryPhoto::findOrFail($id);
        
        return view('master.gallery.iframe-detail', compact('model'));
    }

    /* ------------------------------------------
     EDIT / UPDATE
     ------------------------------------------ */
    public function edit($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstGallery::findOrFail($id);

        $featured = MstGalleryPhoto::where('gallery_id',$id)
                    ->where('featured',1)
                    ->first();

        $images = MstGalleryPhoto::where('gallery_id',$id)
                    ->where('featured',0)
                    ->get();

        return view('master.gallery.update', compact([
            'model',
            'featured',
            'images'
        ])); 
    }

    public function update(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $validate = $request->validate([
            'sort'     => 'required|string|max:1',
            // 'images_id'=> 'required_if:flag,I|required_if:images,null'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstGallery::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'sort'     => $request->sort,
                'active'  => $request->active,
                'updated_by'  => $userId
            ];
            
            if($model->update($data))
            {
                if(strtolower($model->flag) == 'i')
                {
                    $path = base_path().'/public/images/gallery/';
                    /*
                    * Upload featured image baru jika ada
                    */
                    if( $featuredImg = $request->file('featured_img') )
                    {
                        /* get current featured image */
                        $currentFeaturedImg = DB::table('mst_gallery_photo')
                                                ->where('gallery_id', $model->id)
                                                ->where('featured', 1)
                                                ->first();
                        /* delete current featured image */
                        if($currentFeaturedImg)
                        {
                            unlink(base_path().'/public/images/gallery/'.$currentFeaturedImg->photo);
                            DB::table('mst_gallery_photo')
                                    ->where('gallery_id', $model->id)
                                    ->where('featured', 1)
                                    ->delete();
                        }
                        /* upload new featured image */
                        $featured_filename = 'F'.time().rand(10,99).'_'.$featuredImg->getClientOriginalName();
                        $featuredImg->move($path, $featured_filename);

                        $data_img = [
                            'gallery_id'  => $model->id,
                            'photo'     => $featured_filename,
                            'featured'  => 1,
                            'active'    => 1,
                            'created_by'=> $userId,
                            'updated_by'=> $userId
                        ];
                        
                        MstGalleryPhoto::create($data_img);
                    }
                    /*
                    * Delete image yang dihapus di form update
                    */
                    $galleryExisted = DB::table('mst_gallery_photo')
                                        ->where('gallery_id', $model->id)
                                        ->where('featured', 0)
                                        ->get();

                    if( !empty($request->images_id ))
                    {
                        foreach($galleryExisted as $existed)
                        {
                            if( !in_array($existed->id, $request->images_id) )
                            {
                                unlink(base_path().'/public/images/gallery/'.$existed->photo);
                                MstGalleryPhoto::findOrFail($existed->id)->delete();
                            }
                        }
                    }
                    else
                    {
                        foreach($galleryExisted as $existed)
                        {
                            unlink(base_path().'/public/images/gallery/'.$existed->photo);
                            MstGalleryPhoto::findOrFail($existed->id)->delete();
                        }
                    }
                    /*
                    * Upload image baru jika ada
                    */
                    if( $images = $request->file('images') )
                    {
                        foreach($images as $image)
                        {
                            $img_name = 'I'.time().rand(10,99).'_'.$image->getClientOriginalName();
                            $image->move($path, $img_name);

                            $data_img = [
                                'gallery_id'  => $model->id,
                                'photo'     => $img_name,
                                'featured'  => 0,
                                'active'    => 1,
                                'created_by'=> $userId,
                                'updated_by'=> $userId
                            ];
                            
                            MstGalleryPhoto::create($data_img);
                        }
                    }
                }
                else
                {
                    if( !empty($request->featured_iframe) )
                    {
                        DB::table('mst_gallery_photo')
                            ->where('gallery_id', $model->id)
                            ->where('featured', 1)
                            ->update([
                                'iframe'    => $request->featured_iframe,
                                'updated_by'=> $userId
                            ]);
                    }
                    /*
                    * Delete iframe yang dihapus di form update
                    */
                    $galleryExisted = DB::table('mst_gallery_photo')
                                        ->where('gallery_id', $model->id)
                                        ->where('featured', 0)
                                        ->get();

                    foreach($galleryExisted as $existed)
                    {
                        if( !in_array($existed->id, $request->iframes_id) )
                            MstGalleryPhoto::findOrFail($existed->id)->delete();
                    }
                    /*
                    * Upload iframe baru jika ada
                    */
                    if( $request->iframes != null )
                    {
                        foreach($request->iframes as $iframe)
                        {
                            if($iframe != '')
                            {
                                $data_iframe = [
                                    'gallery_id'  => $model->id,
                                    'iframe'     => $iframe,
                                    'featured'  => 0,
                                    'active'    => 1,
                                    'created_by'=> $userId,
                                    'updated_by'=> $userId
                                ];
                                
                                MstGalleryPhoto::create($data_iframe);
                            }
                        }
                    }
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/gallery/'. base64_encode($model->id))->with('success', 'Gallery updated successfully');
            }
            else
                return redirect('master/gallery')->with('error', 'Failed');
        }
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstGalleryLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.gallery.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'  => 'required',
            'description'   => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstGalleryLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'title' => $request->title,
                'description' => $description
            ];

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/gallery/'. base64_encode($model->gallery_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/gallery')->with('error', 'Failed');
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

        DB::update("update mst_gallery set 
            deleted_at = '".$deleted_date."',
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

        $delete = DB::update("update mst_gallery set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/gallery')->with('success', 'Deleted');
        }
        else
            return redirect('master/gallery/show/'.$id)->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::table('mst_gallery as a')
                    ->join('mst_gallery_lang as b','a.id','=','b.gallery_id')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','a.created_at','a.created_by','a.sort','a.flag','b.title')
                    ->orderBy('a.id','desc')
                    ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.gallery.action', [
                    'model' => $model,
                    'url_show'=> route('gallery.show', base64_encode($model->id) ),
                    'url_edit'=> route('gallery.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('gallery.destroy', base64_encode($model->id) )
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->editColumn('flag', function($model){
                if($model->flag == "V")
                    $flag = "Video";
                else
                    $flag = "Foto";

                return $flag;
            })
            // ->addIndexColumn()
            ->rawColumns(['action', 'active', 'flag', 'created_at', 'created_by'])
            ->make(true);
    }

    public function setFeatured(Request $request)
    {
        $id     = $request->get('id');
        $asset  = $request->get('asset');
        // update featured 1 menjadi 0
        MstGalleryPhoto::where('gallery_id',$asset)
                    ->where('featured','1')
                    ->update(['featured'=>'0']);
        // set new featured image
        MstGalleryPhoto::where('id',$id)
                    ->update(['featured'=>'1']);
    }

    public function removeImage(Request $request)
    {
        $value  = $request->get('value');
        $model = MstGalleryPhoto::findOrFail($value);
        $data = [
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => Auth::user()->id,
            'is_deleted' => '1'
        ];
        $model->update($data);
        // unlink(base_path().'/public/images/asset/'.$model->photo);
        // $model->delete();
    }
}
