<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPrivacyPolicy;
use App\Models\MstPrivacyPolicyLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Hash;
use DataTables;
use DB;
use App\Utility;


class MstPrivacyPolicyController extends Controller
{
    const MODULE_NAME = 'Privacy Policy';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.privacy_policy.index');
		//format (folder)
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

        $model = new MstPrivacyPolicy();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.privacy_policy.create', compact('model','language'));
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
            'description'      => 'required|string',
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

            if($model = MstPrivacyPolicy::create($data))
            {
                $data = [
                    'privacy_policy_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'description' => $request->description
                ];

                if($image = $request->file('image'))
                {
                    $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/privacy-policy/';
                    $image->move($path, $image_filename);
                    $data = array_merge($data, ['image'=>$image_filename]);
                }

                if(MstPrivacyPolicyLang::create($data))
                {
                    \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                    return redirect('master/privacy-policy/'. base64_encode($model->id))->with('success', 'Created successfully');
                }
                else
                    return redirect('master/privacy-policy')->with('error', 'Failed');
            }
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstPrivacyPolicyLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.privacy_policy.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'description'      => 'required|string',
            'privacy_policy_id'   => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'privacy_policy_id' => $request->privacy_policy_id,
                'code' => $request->code,
                'title' => $request->title,
                'description' => $description
            ];

            if($image = $request->file('image'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/privacy-policy/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
            }

            if($model = MstPrivacyPolicyLang::create($data))
            {
                $sg = MstPrivacyPolicy::findOrFail($model->privacy_policy_id);
                if($sg->isComplete())
                {
                    $sg->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/privacy-policy/'. base64_encode($model->privacy_policy_id))->with('success', 'Created successfully');
            }
            else
                return redirect('master/privacy-policy')->with('error', 'Failed');
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

        $pp = MstPrivacyPolicy::findOrFail($id);

        $model = DB::table('mst_privacy_policy as p')
                    ->join('mst_privacy_policy_lang as pl','p.id','pl.privacy_policy_id')
                    ->where('p.id', $id)
                    ->where('pl.code', 'IND')
                    ->select(
                        'pl.id as privacy_policy_lang_id',
                        'pl.title',
                        'pl.description',
                        'pl.image',
                        'p.*'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.privacy_policy.detail', compact([
            'pp', 
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

        $model = MstPrivacyPolicy::findOrFail($id);

        return view('master.privacy_policy.update', compact('model'));
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
            $model = MstPrivacyPolicy::findOrFail($id);
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
                return redirect('master/privacy-policy/'. base64_encode($model->id))->with('success', 'Updated successfully');
            }
            else
                return redirect('master/privacy-policy')->with('error', 'Failed');
        }
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $id = base64_decode($id);

        $model = MstPrivacyPolicyLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.privacy_policy.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');
        
        $validate = $this->validate($request, [
            'title'     => 'required',
            'description'   => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstPrivacyPolicyLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $description = str_replace('<pre>', '<p>', $request->description);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'title' => $request->title,
                'description' => $description
            ];

            $path = base_path().'/public/images/privacy-policy/';

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
                return redirect('master/privacy-policy/'. base64_encode($model->privacy_policy_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/privacy-policy')->with('error', 'Failed');
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

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;
		
        DB::update("update mst_privacy_policy set 
            deleted_date='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }
	
	
	public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_privacy_policy set 
            deleted_date='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/privacy-policy')->with('success', 'Deleted');
        }
        else
            return redirect('master/privacy-policy')->with('error', 'Failed');
    }
	
	
	///////=====function DataTabel=====///// 
	
	public function dataTable()
    {
        // $model = MstPrivacyPolicy::query();
        // $model->where('is_deleted','<>','1');
        // $model->orderBy('id','desc');
        $model = DB::select("
            select 
                pp.id,
                ppl.title
            from 
                mst_privacy_policy as pp
            join mst_privacy_policy_lang as ppl on ppl.privacy_policy_id = pp.id 
            where 
                ppl.code = 'IND'
            and pp.is_deleted = 0
            order by pp.id desc;
        ");
		
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.privacy_policy.action', [
                    'model' => $model,
                    'url_show'=> route('privacy-policy.show', base64_encode($model->id) ),
                    'url_edit'=> route('privacy-policy.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('privacy-policy.destroy', base64_encode($model->id) )
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
}
