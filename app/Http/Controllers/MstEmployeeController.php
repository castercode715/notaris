<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstEmployee;
use App\Models\SecRole;
use Illuminate\Support\Facades\Auth;
use Hash;
use DataTables;
use DB;
use App\Utility;

class MstEmployeeController extends Controller
{
    const MODULE_NAME = 'Employee';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.employee.index');
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

        $model = new MstEmployee();
        $countries = DB::table('mst_countries')->get();
        $roles = SecRole::where('active','1')
                ->where('is_deleted','0')
                ->pluck('role','id')
                ->all();

        return view('master.employee.create', compact(['model','countries','roles']));
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

        $this->validate($request, [
            'full_name'     => 'required|string',
            'username'      => 'required|string|unique:mst_employee,username,1,is_deleted',
            'password'      => 'required|required_with:password_confirmation|string|confirmed',
            'gender'        => 'required',
            'birth_place'   => 'required|string',
            'birth_date'    => 'required',
            'country'       => 'required',
            'province'       => 'required',
            'regency'       => 'required',
            'district'       => 'required',
            'villages_id'       => 'required',
            'zip_code'       => 'required',
            'address'       => 'required',
            'email'       => 'required|string',
            'phone'       => 'required|string',
            'photo'       => 'required|file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $image = $request->file('photo');
        $filename = time().$image->getClientOriginalName();
        $path = base_path().'/public/images/employee/';
        $image->move($path, $filename);

        $data = [
            'full_name'  => $request->full_name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'gender'  => $request->gender,
            'birth_place'  => $request->birth_place,
            'birth_date'  => date('Y-m-d', strtotime($request->birth_date)),
            'villages_id'  => $request->villages_id,
            'address'  => $request->address,
            'zip_code'  => $request->zip_code,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'photo'     => $filename,
            'active'    => $active,
            'role_id' => $request->role_id,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];

        $model = MstEmployee::create($data);
        if($model)
        {
            \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
            return redirect('master/employee/'. base64_encode($model->id))->with('success','Employee successfully created');
        }
        else
            Redirect::back()->withErrors(['error', 'Failed']);
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
        $model = MstEmployee::findOrFail($id);
        $uti = new Utility();
        $address = $uti->getDetailAddress($model->villages_id);
        return view('master.employee.detail', compact(['model', 'uti', 'address']));
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

        $model = MstEmployee::findOrFail($id);

        $uti = new Utility();
        
        $address = $uti->getDetailAddress($model->villages_id);
        
        $countries = DB::table('mst_countries')->get();
        
        $provinces = DB::table('mst_provinces')->where('countries_id',$address['country_id'])->get();
        
        $regencies = DB::table('mst_regencies as r')
                    ->join('mst_regencies_lang as rl','r.id','rl.regencies_id')
                    ->where('provinces_id',$address['province_id'])
                    ->where('rl.code', "IND")
                    ->select('r.id','rl.name')
                    ->get();
        
        $districts = DB::table('mst_districts')->where('regencies_id',$address['regency_id'])->get();
        
        $villages = DB::table('mst_villages')->where('districts_id',$address['district_id'])->get();
        
        $model->birth_date = date('d-m-Y', strtotime($model->birth_date));
        
        $roles = SecRole::where('active','1')
                ->where('is_deleted','0')
                ->pluck('role','id')
                ->all();

        return view('master.employee.update', compact([
            'model', 
            'countries', 
            'provinces', 
            'regencies', 
            'districts', 
            'villages', 
            'uti', 
            'address',
            'roles'
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

        $id = base64_decode($id);
        $this->validate($request, [
            'full_name'     => 'required|string',
            'username'      => 'required|string|unique:mst_employee,username,'.$id.',id,is_deleted,0',
            'password'      => 'nullable|required_with:password_confirmation|string|confirmed',
            'gender'        => 'required',
            'birth_place'   => 'required|string',
            'birth_date'    => 'required',
            'country'       => 'required',
            'province'       => 'required',
            'regency'       => 'required',
            'district'       => 'required',
            'villages_id'       => 'required',
            'zip_code'       => 'required',
            'address'       => 'required',
            'email'       => 'required|string',
            'phone'       => 'required|string',
            'photo'       => 'file|image|mimes:jpg,jpeg,png|max:5048'
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;

        $model = MstEmployee::findOrFail($id);

        if($request->file('photo'))
        {
            $image = $request->file('photo');
            $filename = time().$image->getClientOriginalName();
            $path = base_path().'/public/images/employee/';
            $image->move($path, $filename);

            if($request->password != '')
            {
                $data = [
                    'full_name'  => $request->full_name,
                    'username'  => $request->username,
                    'password'  => Hash::make($request->password),
                    'gender'  => $request->gender,
                    'birth_place'  => $request->birth_place,
                    'birth_date'  => date('Y-m-d', strtotime($request->birth_date)),
                    'villages_id'  => $request->villages_id,
                    'address'  => $request->address,
                    'zip_code'  => $request->zip_code,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'photo'     => $filename,
                    'active'    => $active,
                    'role_id' => $request->role_id,
                    'updated_by'=> $userId
                ];
            } else {
                $data = [
                    'full_name'  => $request->full_name,
                    'username'  => $request->username,
                    'gender'  => $request->gender,
                    'birth_place'  => $request->birth_place,
                    'birth_date'  => date('Y-m-d', strtotime($request->birth_date)),
                    'villages_id'  => $request->villages_id,
                    'address'  => $request->address,
                    'zip_code'  => $request->zip_code,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'photo'     => $filename,
                    'active'    => $active,
                    'role_id' => $request->role_id,
                    'updated_by'=> $userId
                ];
            }

            if($model->photo != null)
                unlink(base_path().'/public/images/employee/'.$model->photo);
        } else {
            if($request->password != '')
            {
                $data = [
                    'full_name'  => $request->full_name,
                    'username'  => $request->username,
                    'password'  => Hash::make($request->password),
                    'gender'  => $request->gender,
                    'birth_place'  => $request->birth_place,
                    'birth_date'  => date('Y-m-d', strtotime($request->birth_date)),
                    'villages_id'  => $request->villages_id,
                    'address'  => $request->address,
                    'zip_code'  => $request->zip_code,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'active'    => $active,
                    'role_id' => $request->role_id,
                    'updated_by'=> $userId
                ];
            } else {
                $data = [
                    'full_name'  => $request->full_name,
                    'username'  => $request->username,
                    'gender'  => $request->gender,
                    'birth_place'  => $request->birth_place,
                    'birth_date'  => date('Y-m-d', strtotime($request->birth_date)),
                    'villages_id'  => $request->villages_id,
                    'address'  => $request->address,
                    'zip_code'  => $request->zip_code,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'active'    => $active,
                    'role_id' => $request->role_id,
                    'updated_by'=> $userId
                ];
            }
        }

        if($model->update($data))
        {
            \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
            return redirect('master/employee/'. base64_encode($model->id))->with('success','Data Updated');
        }
        else
            Redirect::back()->withErrors(['error', 'Failed']);
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

        DB::update("update mst_employee set 
            deleted_at ='".$deleted_date."',
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

        $delete = DB::update("update mst_employee set 
            deleted_at ='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
        
        if($delete)
        {
            \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
            return redirect('master/employee')->with('success', 'Deleted');
        }
        else
            return redirect('master/employee')->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = MstEmployee::query();
        $model->where('is_deleted','<>','1');

        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.employee.action', [
                    'model' => $model,
                    'url_show'=> route('employee.show', base64_encode($model->id) ),
                    'url_edit'=> route('employee.edit', base64_encode($model->id) ),
                    'url_destroy'=> route('employee.destroy', base64_encode($model->id) )
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

    public function fetch(Request $request)
    {
        $select = $request->get('id');
        $value  = $request->get('value');
        $table = $request->get('table');
        $key = $request->get('key');

        $data = array();
        if($table != 'mst_regencies')
        {
            $data = DB::table($table)
                    ->where($key,'=',$value)
                    ->get();
        }
        else
        {
            $data = DB::table('mst_regencies as r')
                    ->join('mst_regencies_lang as rl','r.id','rl.regencies_id')
                    ->where('rl.code',"IND")
                    ->where($key,$value)
                    ->select('r.id','rl.name')
                    ->get();
        }
        
        $return = "<option value=''>- Select -</option>";
        foreach($data as $row)
        {
            $return .= "<option value='".$row->id."'>".$row->name."</option>";
        }
        echo $return;
    }
}
