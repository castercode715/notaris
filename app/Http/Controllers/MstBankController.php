<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstBank;
use App\Models\MstCountries;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class MstBankController extends Controller
{

    const MODULE_NAME = 'Bank';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');
        
        return view('master.bank.index');
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

        $model = new MstBank();

        $country = MstCountries::pluck('name','id')->all();

        return view('master.bank.create', compact('model','country'));
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

        $this->validate($request,[
            'name'          => 'required|string',
            'card_type'     => 'required|string',
            'countries_id'     => 'required',
            'image_logo'    => 'file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;


        $image = $request->file('image_logo');
        $filename = time().$image->getClientOriginalName();
        $path = base_path().'/public/images/bank/';
        $image->move($path, $filename);

        $data = [
            'name'      => $request->name,
            'card_type' => $request->card_type,
            'image_logo'=> $filename,
            'countries_id'=> $request->countries_id,
            'active'    => $active,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];

        $model = MstBank::create($data);
        \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
        return redirect('master/bank')->with('success','Success');
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


        $model = MstBank::findOrFail($id);
        $uti = new Utility();
        return view('master.bank.detail', compact(['model','uti']));
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

        $a = ['trc_inv_bank'];
        $b = ['bank_id'];


        if(!$this->isAllowedToUpdate($a, $b, $id))
            abort(401, 'Table has related.');
        $model = MstBank::findOrFail($id);
        $country = MstCountries::pluck('name','id')->all();
        return view('master.bank.update', compact('model','country'));
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

        $this->validate($request,[
            'name'          => 'required|string',
            'card_type'     => 'required|string',
            'countries_id'     => 'required',
            'image_logo'    => 'file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        $model = MstBank::findOrFail($id);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;        

        if($request->file('image_logo'))
        {
            $image = $request->file('image_logo');
            $filename = time().$image->getClientOriginalName();
            $path = base_path().'/public/images/bank/';
            $image->move($path, $filename);

            $data = [
                'name'      => $request->name,
                'card_type' => $request->card_type,
                'countries_id' => $request->countries_id,
                'image_logo'=> $filename,
                'active'    => $active,
                'updated_by'=> $userId
            ];

            if($model->image_logo != null)
                unlink(base_path().'/public/images/bank/'.$model->image_logo);            
        }
        else
        {
            $data = [
                'name'      => $request->name,
                'card_type' => $request->card_type,
                'countries_id' => $request->countries_id,
                'active'    => $active,
                'updated_by'=> $userId
            ];   
        }

        $model->update($data);
        \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
        return redirect('master/bank')->with('success','Success');
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
        // $model = MstBank::findOrFail($id);
        // unlink(base_path().'/public/images/bank/'.$model->image_logo);
        // $model->delete();

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_bank set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = MstBank::query();
        $model->where('is_deleted','<>','1');
        $model->orderBy('created_at','desc');

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.bank.action', [
                    'model' => $model,
                    'url_show'=> route('bank.show', $model->id),
                    'url_edit'=> route('bank.edit', $model->id),
                    'url_destroy'=> route('bank.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->addColumn('card_type', function($model){
                return $model->getCardType();
            })
            ->addColumn('image_logo', function($model){
                return "<img src='".'/images/bank/'.$model->image_logo."' width='100px' />";
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'card_type', 'image_logo', 'create_at', 'created_by'])
            ->make(true);
    }
}
