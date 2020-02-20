<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstAboutUs;
use App\Models\MstAboutUsLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Hash;
use DataTables;
use DB;
use App\Utility;


class MstAboutUsController extends Controller
{
    const MODULE_NAME = 'About Us';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

         return view('master.about_us.index');
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

        $model = new MstAboutUs();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.about_us.create', compact('model','language'));
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
            'title'     => 'required|string',
            'sort'      => 'required',
            'image'       => 'file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;
            
            $data = [
                'sort' => $request->sort,
                'active' => 0,
                'created_by'=> $userId,
                'updated_by'=> $userId
            ];

            if($model = MstAboutUs::create($data))
            {
                $data = [
                    'about_us_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'description' => $request->description,
                    'iframe' => $request->iframe
                ];

                if($image = $request->file('image'))
                {
                    $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/about-us/';
                    $image->move($path, $image_filename);
                    $data = array_merge($data, ['image'=>$image_filename]);
                }

                if(MstAboutUsLang::create($data))
                {
                    \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                    return redirect('master/about-us/'. base64_encode($model->id))->with('success', 'Created successfully');
                }
                else
                    return redirect('master/about-us')->with('error', 'Failed');
            }
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstAboutUsLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.about_us.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'about_us_id'   => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'about_us_id' => $request->about_us_id,
                'code' => $request->code,
                'title' => $request->title,
                'description' => $description,
                'iframe' => $request->iframe
            ];

            if($image = $request->file('image'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/about-us/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
            }

            if($model = MstAboutUsLang::create($data))
            {
                $sg = MstAboutUs::findOrFail($model->about_us_id);
                if($sg->isComplete())
                {
                    $sg->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/about-us/'. base64_encode($model->about_us_id))->with('success', 'Created successfully');
            }
            else
                return redirect('master/about-us')->with('error', 'Failed');
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

        $au = MstAboutUs::findOrFail($id);

        $model = DB::table('mst_about_us as p')
                    ->join('mst_about_us_lang as pl','p.id','pl.about_us_id')
                    ->where('p.id', $id)
                    ->where('pl.code', 'IND')
                    ->select(
                        'pl.id as about_us_lang_id',
                        'pl.title',
                        'pl.description',
                        'pl.image',
                        'pl.iframe',
                        'p.*'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.about_us.detail', compact([
            'au', 
            'model', 
            'language', 
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

        $model = MstAboutUs::findOrFail($id);

        return view('master.about_us.update', compact('model'));
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
            'sort'     => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstAboutUs::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'sort'     => $request->sort,
                'active'  => $request->active,
                'updated_by'  => $userId
            ];
            
            $model->update($data);
            if($model)
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/about-us/'. base64_encode($model->id))->with('success', 'Updated successfully');
            }
            else
                return redirect('master/about-us')->with('error', 'Failed');
        }
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $id = base64_decode($id);

        $model = MstAboutUsLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.about_us.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $validate = $this->validate($request, [
            'title'     => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstAboutUsLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'title' => $request->title,
                'description' => $description,
                'iframe' => $request->iframe
            ];

            $path = base_path().'/public/images/about-us/';

            $unlink = false;
            if($model->image)
            {
                if($request->img == '' || $request->img == null)
                {
                    unlink($path.$model->image);
                    $unlink = true;
                    $data = array_merge($data, ['image'=>null]);
                }
            }
                

            if($image = $request->file('image'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
                // delete old image
                if ($model->image && !$unlink)
                    unlink($path.$model->image);
            }

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/about-us/'. base64_encode($model->about_us_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/about-us')->with('error', 'Failed');
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
        
        DB::update("update mst_about_us set 
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

        $delete = DB::update("update mst_about_us set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/about-us')->with('success', 'Deleted');
        }
        else
            return redirect('master/about-us')->with('error', 'Failed');
    }

    public function dataTable()
    {
        /*$model = DB::table('mst_about_us as a')
                    ->join('mst_about_us_lang as b','a.id','b.about_us_id')
                    ->join('mst_language as c','b.code','c.code')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','b.title','a.created_at','a.created_by')
                    ->get();*/
        $model = DB::select("
            select 
                au.id,
                aul.title
            from 
            mst_about_us as au,
            mst_about_us_lang as aul 
            where 
                aul.about_us_id = au.id 
            and aul.code = 'IND'
            and au.is_deleted = 0
            order by 
                au.id desc;
        ");
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.about_us.action', [
                    'model' => $model,
                    'url_show'=> route('about-us.show', base64_encode($model->id) ),
                    'url_edit'=> route('about-us.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('about-us.destroy', base64_encode($model->id) )
                ]);
            })
            // ->editColumn('created_at', function($model){
            //     return date('d-m-Y H:i:s', strtotime($model->created_at));
            // })
            // ->editColumn('created_by', function($model){
            //     $uti = new utility();
            //     return $uti->getUser($model->created_by);
            // })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function removeImage(Request $request)
    {
        $id  = $request->get('value');
        $model = MstAboutUs::findOrFail($id);
        $photo = $model->image;
        $data = [
            'image' => null
        ];
        if($model->update($data))
            unlink(base_path().'/public/images/about-us/'.$photo);

         
    }

    public function show_detail($id)
    {
        $id = base64_decode($id);
        $lang = DB::table('mst_about_us_lang as a')
                ->join('mst_language as b', 'a.code', 'b.code')
                ->select('a.*','b.*')
                ->where('a.id', $id)
                ->first();
        $model = MstAboutUsLang::findOrFail($id);
        $uti = new Utility();
        return view('master.about_us.update', compact(['lang','model','uti']));
    }

    public function updateData(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $id = base64_decode($id);
        $this->validate($request,[
           'title'     => 'required|string',
           'description'      => 'required|string',
           'iframe'      => 'required|string',
           'image'      => 'file|image|mimes:jpg,jpeg,png|max:5048',
            // 'sort'      => 'required|string|unique:mst_about_us,sort,'.$id.',id,is_deleted,0',
        ]);

        $model = MstAboutUsLang::findOrFail($id);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;    
        $description = str_replace('<pre>', '<p>', $request->description);
        $description = str_replace('</pre>', '</p>', $description);    

        if($request->file('image'))
        {
            $image = $request->file('image');
            $filename = time().$image->getClientOriginalName();
            $path = base_path().'/public/images/about-us/';
            $image->move($path, $filename);

            $data = [
            'title'     => $request->title,
            'description'     => $description,
            'iframe'     => $request->iframe,
            'image'     => $filename,
            ];

            if($model->image != null)
                unlink(base_path().'/public/images/about-us/'.$model->image);            
        }
        else
        {
            $data = [
            'title'     => $request->title,
            'description'     => $description,
            'iframe'     => $request->iframe,
            ];   
        }

        $model->update($data);
        \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
        return redirect('master/about-us/'.base64_encode($model->about_us_id))->with('success','Updated !');
    }
}
