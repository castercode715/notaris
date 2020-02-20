<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstHelp;
use App\Models\MstHelpLang;
use Illuminate\Support\Facades\Auth;
use App\Models\MstLanguage;
use DataTables;
use DB;
use App\Utility;

class MstHelpController extends Controller
{
    const MODULE_NAME = 'Help';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.help.index');
    }

    /* ------------------------------------------
     CREATE / STORE
     ------------------------------------------ */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');
        
        $model = new MstHelp();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.help.create', compact(['model','language']));
    }

    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'desc'      => 'required|string',
            'sort'      => 'required',
            'flag'      => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $data = [
                'sort' => $request->sort,
                'active' => 0,
                'flag' => $request->flag,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

            if($model = MstHelp::create($data))
            {
                $data = [
                    'help_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'description' => $request->desc,
                    'iframe' => $request->iframe
                ];

                if($image = $request->file('photo'))
                {
                    $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/help/';
                    $image->move($path, $image_filename);
                    $data = array_merge($data, ['image'=>$image_filename]);
                }

                if(MstHelpLang::create($data))
                {
                    \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                    return redirect('master/help/'. base64_encode($model->id))->with('success', 'Help created successfully');
                }
                else
                    return redirect('master/help')->with('error', 'Failed');
            }
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstHelpLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.help.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'desc'      => 'required|string',
            'help_id'   => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->desc);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'help_id' => $request->help_id,
                'code' => $request->code,
                'title' => $request->title,
                'description' => $description,
                'iframe' => $request->iframe
            ];

            if($image = $request->file('photo'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/help/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
            }

            if($model = MstHelpLang::create($data))
            {
                $help = MstHelp::findOrFail($model->help_id);
                if($help->isComplete())
                {
                    $help->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/help/'. base64_encode($model->help_id))->with('success', 'Help created successfully');
            }
            else
                return redirect('master/help')->with('error', 'Failed');
        }
    }

    /* ------------------------------------------
     EDIT / UPDATE
     ------------------------------------------ */
    public function edit($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstHelp::findOrFail($id);

        return view('master.help.update', compact('model')); 
    }

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
            $model = MstHelp::findOrFail($id);
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
                return redirect('master/help/'. base64_encode($model->id))->with('success', 'Help updated successfully');
            }
            else
                return redirect('master/help')->with('error', 'Failed');
        }
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstHelpLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.help.update_new', compact(['model','language']));
    }

    public function updateNew(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required',
            'desc'   => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstHelpLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'title' => $request->title,
                'description' => $request->desc,
                'iframe' => $request->iframe
            ];

            if($model->image)
            {
                if($request->img == '' || $request->img == null)
                {
                    unlink(base_path().'/public/images/help/'.$model->image);
                    $data = array_merge($data, ['image'=>null]);
                }
            }

            if($image = $request->file('photo'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/help/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
                // delete old image
                if($model->image)
                    unlink(base_path().'/public/images/help/'.$model->image);
            }

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/help/'. base64_encode($model->help_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/help')->with('error', 'Failed');
        }
    }

    /* ------------------------------------------
     DETAIL
     ------------------------------------------ */
    public function show($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');
            
        $id = base64_decode($id);

        $help = MstHelp::findOrFail($id);

        $model = DB::table('mst_help as h')
                    ->join('mst_help_lang as hl','h.id','hl.help_id')
                    ->where('h.id', $id)
                    ->where('hl.code', 'IND')
                    ->select(
                        'hl.id as help_lang_id',
                        'hl.title',
                        'hl.description',
                        'hl.image',
                        'hl.iframe',
                        'h.*'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.help.detail', compact([
            'help', 
            'model', 
            'language', 
            'uti'
        ]));
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

        DB::update("update mst_help set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    /* ------------------------------------------
     DELETE
     ------------------------------------------ */
    public function delete($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_help set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/help')->with('success', 'Deleted');
        }
        else
            return redirect('master/help')->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::table('mst_help as a')
                    ->join('mst_help_lang as b','a.id','=','b.help_id')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    // ->select('a.id','a.created_at','a.created_by','b.title', 'a.flag as category')
                    ->selectRaw("a.id, a.created_at, a.created_by, b.title, CASE WHEN a.flag = 'faq' THEN 'FAQ' WHEN a.flag = 'how-to' THEN 'HOW TO' END as category")
                    ->orderBy('a.id','desc')
                    ->get();

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.help.action', [
                    'model' => $model,
                    'url_show'=> route('help.show', base64_encode($model->id)),
                    'url_edit'=> route('help.edit', base64_encode($model->id)),
                    'url_destroy'=> route('help.destroy', base64_encode($model->id))
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

    public function removeImage(Request $request)
    {
        $id  = $request->get('value');
        $model = MstHelp::findOrFail($id);
        $photo = $model->photo;
        $data = [
            'photo' => null
        ];
        if($model->update($data))
            unlink(base_path().'/public/images/help/'.$photo);
    }
}
