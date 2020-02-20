<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstMember;
use App\Models\MstMemberLang;
use App\Models\MstLanguage;
use App\Rules\MstMemberDescriptionCreateRule;

use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;
use Crypt;

class MstMemberController extends Controller
{
    const MODULE_NAME = 'Member';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');
        
        return view('master.member.index');
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

        $model = new MstMember;
        $language = MstLanguage::all();

        return view('master.member.create', compact(['model','language']));
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
            'description.*'  => 'required',
            // 'description.*'  => 'required|distinct|min:1',
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

            if($model = MstMember::create($data))
            {
                foreach($request->code as $key => $value)
                {
                    if($request->file('image'))
                    {
                        if(array_key_exists($key, $request->file('image')))
                        {
                            $images = $request->file('image')[$key];
                            $img_name = time().rand(100, 999).$images->getClientOriginalName();
                            $path = base_path().'/public/images/member/';
                            $images->move($path, $img_name); 
                            $data = [
                                'member_id'      => $model->id,
                                'code'          => $request->code[$key],
                                'description'   => $request->description[$key],
                                'image'         => $img_name
                            ];
                            MstMemberLang::create($data);
                        }
                    }
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/member/'. base64_encode($model->id) )->with('success','Success');
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

        $model = MstMember::findOrFail($id);
        $data = DB::table('mst_member_lang as a')
                    ->join('mst_member as b','a.member_id','=','b.id')
                    ->join('mst_language as c','a.code','=','c.code')
                    ->where('a.member_id',$id)
                    ->select('a.*','c.language')
                    ->get();

        $uti = new Utility();
        return view('master.member.detail', compact(['model', 'data', 'uti']));
    }

    public function newLanguage($id)
    {
        $id = base64_decode($id);

        $model = MstMemberLang::findOrFail($id);

        return view('master.member.lang.create', compact('model'));
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

        $model = MstMember::findOrFail($id);

        $data = DB::table('mst_member_lang as a')
                ->join('mst_language as b','a.code','=','b.code')
                ->where('a.member_id', $id)
                ->select('a.*','b.language')
                ->get();

        return view('master.member.edit', compact(['model','language','data']));
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
            // 'description.*'  => 'required|distinct|min:1'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $userId = Auth::user()->id;
            $active = $request->get('active') ? 1 : 0;
            $model = MstMember::findOrFail($id);

            $data = [
                'active'        => $active,
                'updated_by'    => $userId
            ];

            if($model->update($data))
            {
                foreach($request->id as $key => $value)
                {
                    $member = MstMemberLang::findOrFail($value);

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
                            $path = base_path().'/public/images/member/';
                            $images->move($path, $img_name); 
                            $data = array_merge($data, ['image' => $img_name]);
                        }
                    }
                    // var_dump($data);
                    $member->update($data);
                }
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                return redirect('master/member/'. base64_encode($model->id) )->with('success','Success');
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

        DB::update("update mst_member set 
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

        $delete = DB::update("update mst_member set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/member')->with('success', 'Deleted');
        }
        else
            return redirect('master/member/'.base64_encode($id))->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::select("
            SELECT 
                m.id, ml.id as memberid, ml.description, ml.image
            FROM
                mst_member AS m,
                mst_member_lang AS ml
            WHERE
                ml.member_id = m.id 
            AND ml.code = 'IND'
            AND m.is_deleted = 0
            ORDER BY m.id , ml.id;
        ");        


        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.member.action', [
                    'model' => $model,
                    'url_show'=> route('member.show', base64_encode($model->id) ),
                    'url_edit'=> route('member.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('member.destroy', base64_encode($model->id) )
                ]);
            })
            ->addColumn('image', function($model){
                return "<img src='".'/images/member/'.$model->image."' width='100px' />";
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function memberLangTable($id)
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
                mst_member_lang as a,
                mst_language as b 
            where 
                a.code = b.code 
            and a.member_id = ".$id."
        ");

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.member.action-detail', [
                    'model' => $model,
                    'url_edit'=> route('member.edit-lang', base64_encode($model->id) ),
                    // 'url_destroy'=> route('member.remove-lang', base64_encode($model->id) )
                ]);
            })
            ->editColumn('image', function($model){
                return '<img src="/images/member/'.$model->image.'" class="img-responsive" width="50px" />';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'image'])
            ->make(true);
    }
}
