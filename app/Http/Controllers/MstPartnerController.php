<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPartner;
use App\Models\MstPartnerLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Hash;
use DataTables;
use DB;
use App\Utility;

class MstPartnerController extends Controller
{
    const MODULE_NAME = 'Partner';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.partner.index');
    }

    /* ------------------------------------------
     CREATE / STORE
     ------------------------------------------ */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $model = new MstPartner();

        $language = MstLanguage::where('code','IND')->first();

        return view('master.partner.create', compact(['model','language']));
    }

    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'desc'      => 'required|string',
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

            if($model = MstPartner::create($data))
            {
                $data = [
                    'partner_id' => $model->id,
                    'code' => $request->code,
                    'title' => $request->title,
                    'description' => $request->desc,
                    'iframe' => $request->iframe
                ];

                if($image = $request->file('photo'))
                {
                    $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                    $path = base_path().'/public/images/partner/';
                    $image->move($path, $image_filename);
                    $data = array_merge($data, ['image'=>$image_filename]);
                }

                if(MstPartnerLang::create($data))
                {
                    \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
                    return redirect('master/partner/'. base64_encode($model->id))->with('success', 'Created successfully');
                }
                else
                    return redirect('master/partner')->with('error', 'Failed');
            }
        }
    }

    public function createNew($id, $lg)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = new MstPartnerLang;

        $language = MstLanguage::where('code', $lg)->first();

        return view('master.partner.create_new', compact(['id','model','language']));
    }

    public function storeNew(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'title'     => 'required|string',
            'desc'      => 'required|string',
            'partner_id'   => 'required'
        ]);

        if($validate)
        {
            $userId = Auth::user()->id;

            $description = str_replace('<pre>', '<p>', $request->desc);
            $description = str_replace('</pre>', '</p>', $description);

            $data = [
                'partner_id' => $request->partner_id,
                'code' => $request->code,
                'title' => $request->title,
                'description' => $description,
                'iframe' => $request->iframe
            ];

            if($image = $request->file('photo'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/partner/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
            }

            if($model = MstPartnerLang::create($data))
            {
                $partner = MstPartner::findOrFail($model->partner_id);
                if($partner->isComplete())
                {
                    $partner->update([
                        'active'    => 1,
                        'updated_by' => $userId,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/partner/'. base64_encode($model->partner_id))->with('success', 'Partner created successfully');
            }
            else
                return redirect('master/partner')->with('error', 'Failed');
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

        $partner = MstPartner::findOrFail($id);

        $model = DB::table('mst_partner as p')
                    ->join('mst_partner_lang as pl','p.id','pl.partner_id')
                    ->where('p.id', $id)
                    ->where('pl.code', 'IND')
                    ->select(
                        'pl.id as partner_lang_id',
                        'pl.title',
                        'pl.description',
                        'pl.image',
                        'pl.iframe',
                        'p.*'
                    )
                    ->first();

        $language = MstLanguage::whereNotIn('code',['IND'])->get();

        $uti = new Utility();
        return view('master.partner.detail', compact([
            'partner', 
            'model', 
            'language', 
            'uti'
        ]));
    }

    /* ------------------------------------------
     EDIT / UPDATE
     ------------------------------------------ */
    public function edit($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstPartner::findOrFail($id);

        return view('master.partner.update', compact('model')); 
    }

    public function update(Request $request, $id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'sort'     => 'required',
            'active'   => 'required'
        ]);

        if($validate)
        {
            $id = base64_decode($id);
            $model = MstPartner::findOrFail($id);
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
                return redirect('master/partner/'. base64_encode($model->id))->with('success', 'Partner updated successfully');
            }
            else
                return redirect('master/partner')->with('error', 'Failed');
        }
    }

    public function editNew($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstPartnerLang::findOrFail($id);

        $language = MstLanguage::where('code', $model->code)->first();

        return view('master.partner.update_new', compact(['model','language']));
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
            $model = MstPartnerLang::findOrFail($id);
            $userId = Auth::user()->id;
            $date = date('Y-m-d H:i:s');

            $data = [
                'title' => $request->title,
                'description' => $request->desc,
                'iframe' => $request->iframe
            ];

            if($request->img == '' || $request->img == null)
            {
                
                $data = array_merge($data, ['image'=>null]);
            }

            if($image = $request->file('photo'))
            {
                $image_filename = time().rand(10,99).'_'.$image->getClientOriginalName();
                $path = base_path().'/public/images/partner/';
                $image->move($path, $image_filename);
                $data = array_merge($data, ['image'=>$image_filename]);
                // delete old image
                
            }

            if($model->update($data))
            {
                \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Languange '.$model->code.' Successfully');
                return redirect('master/partner/'. base64_encode($model->partner_id))->with('success', 'Description updated');
            }
            else
                return redirect('master/partner')->with('error', 'Failed');
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
        
        DB::update("update mst_partner set 
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

        $delete = DB::update("update mst_partner set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/partner')->with('success', 'Deleted');
        }
        else
            return redirect('master/partner')->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = DB::table('mst_partner as a')
                    ->join('mst_partner_lang as b','a.id','=','b.partner_id')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','a.created_at','a.created_by','a.sort','b.title')
                    ->orderBy('a.id','desc')
                    ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.partner.action', [
                    'model' => $model,
                    'url_show'=> route('partner.show', base64_encode($model->id) ),
                    'url_edit'=> route('partner.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('partner.destroy', base64_encode($model->id) )
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            // ->addIndexColumn()
            ->rawColumns(['action', 'active', 'created_at', 'created_by'])
            ->make(true);
    }
}
