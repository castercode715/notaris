<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstClass;
use App\Models\MstClassLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstClassController extends Controller
{
    const MODULE_NAME = 'Class';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.class.index');
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

        $model = new MstClassLang;

        $language = MstLanguage::all();

        return view('master.class.create', compact(['model','language']));
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
            'description.*'  => 'required|distinct|min:1',
            'image.*'  => 'required|max:5048',
            'active'    => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $data = [
                'active'        => 1,
                'created_by'    => $userId,
                'updated_by'    => $userId
            ];

            if($model = MstClass::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    if($request->file('image'))
                    {
                        if(array_key_exists($key, $request->file('image')))
                        {
                            $images = $request->file('image')[$key];
                            $img_name = time().rand(100, 999).$images->getClientOriginalName();
                            $path = base_path().'/public/images/class/';
                            $images->move($path, $img_name); 
                            $data = [
                                'class_id'      => $model->id,
                                'code'          => $request->code[$key],
                                'description'   => $request->description[$key],
                                'image'         => $img_name
                            ];
                            MstClassLang::create($data);
                        }
                    }
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/class/'. base64_encode($model->id) )->with('success','Success');
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

        $model = MstClass::findOrFail($id);
        $data = DB::table('mst_class_lang as a')
                    ->join('mst_class as b','a.class_id','=','b.id')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.class_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        $uti = new Utility();
        return view('master.class.detail', compact(['model', 'data', 'uti']));
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

        $model = MstClass::findOrFail($id);

        $data = DB::table('mst_class_lang as a')
                ->join('mst_language as b','a.code','=','b.code')
                ->where('a.class_id', $id)
                ->select('a.*','b.language')
                ->get();

        return view('master.class.edit', compact(['model','language','data']));
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
            'active'  => 'required',
            'code.*'  => 'required|distinct|min:1',
            'description.*'  => 'required|distinct|min:1'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            $model = MstClass::findOrFail($id);

            $data = [
                'active'        => $active,
                'updated_by'    => $userId
            ];

            if($model->update($data))
            {
                foreach($request->id as $key => $value)
                {
                    $class = MstClassLang::findOrFail($value);

                    $data = [
                        'code'          => $request->code[$key],
                        'description'   => $request->description[$key]
                    ];

                    if($request->file('image'))
                    {
                        if(array_key_exists($key, $request->file('image')))
                        {
                            $images = $request->file('image')[$key];
                            $img_name = time().$images->getClientOriginalName();
                            $path = base_path().'/public/images/class/';
                            $images->move($path, $img_name); 
                            $data = array_merge($data, ['image' => $img_name]);
                        }
                    }
                    // var_dump($data);
                    $class->update($data);
                }
                // die();
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/class/'. base64_encode($model->id) )->with('success','Success');
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
        // $model = MstClass::findOrFail($id);
        // $model->delete();
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_class set 
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

        $delete = DB::update("update mst_class set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/class')->with('success', 'Deleted');
        }
        else
            return redirect('master/class/'.base64_encode($id))->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::table('mst_class as a')
                    ->join('mst_class_lang as b','a.id','=','b.class_id')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','a.created_at','a.created_by','b.description','b.image')
                    ->orderBy('a.id','desc')
                    ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.class.action', [
                    'model' => $model,
                    'url_show'=> route('class.show', base64_encode($model->id) ),
                    'url_edit'=> route('class.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('class.destroy', base64_encode($model->id) )
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i', strtotime($model->created_at)).' WIB';
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'created_at', 'created_by'])
            ->make(true);
    }

    public function dataTableDetail($id)
    {
        $id = base64_decode($id);
        $model = DB::select("
            select 
                a.id,
                a.code,
                b.language,
                a.description,
                a.image
            from 
                mst_class_lang as a,
                mst_language as b 
            where 
                a.code = b.code 
            and a.class_id = ".$id."
        ");

        return DataTables::of($model)
            // ->addColumn('action', function($model){
            //     return view('master.class.action-detail', [
            //         'model' => $model,
            //         'url_edit'=> route('class.edit-lang', base64_encode($model->id) ),
            //         // 'url_destroy'=> route('class.remove-lang', base64_encode($model->id) )
            //     ]);
            // })
            ->editColumn('image', function($model){
                return '<img src="/images/class/'.$model->image.'" class="img-responsive" width="50px" />';
            })
            ->addIndexColumn()
            ->rawColumns(['image'])
            ->make(true);
    }

    public function fetchLanguage(Request $request)
    {
        $data = MstLanguage::all();
        
        $return = "<option value=''>- Select -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->code."'>".$row->language."</option>";
        }
        echo $return;
    }

    public function removeLanguage(Request $request)
    {
        $id  = $request->get('id');
        $model = MstClassLang::findOrFail($id);
        unlink(base_path().'/public/images/class/'.$model->image);
        $model->delete();

        \UserLogActivity::addLog('Delete Class ID #'.$model->class_id.' Languange '.$model->code.' Successfully');
    }

    public function editLanguage($id)
    {
        $id = base64_decode($id);
        $model = MstClassLang::findOrFail($id);
        $language = MstLanguage::where('code',$model->code)->first();

        return view('master.class.edit', compact(['model','language']));
    }

    public function updateLanguage(Request $request, $id)
    {
        $validate = $this->validate($request, [
            'description'  => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $data = [
                'code'  => $request->code,
                'description' => $request->description
            ];

            if($image = $request->file('image'))
            {
                $img_name = time().$image->getClientOriginalName();
                $path = base_path().'/public/images/class/';
                $image->move($path, $img_name); 
                $data = array_merge($data, ['image' => $img_name]);
            }

            $model = MstClassLang::findOrFail($id);

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update Class ID #'.$model->class_id.' Languange '.$model->code.' Successfully');

                return redirect('master/class/'. base64_encode($model->class_id))->with('success','Updated');
            }
            else
                Redirect::back()->withErrors(['error', 'Failed']);
        }
    }
}
