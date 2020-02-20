<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstInvestor;
use App\Models\MstBank;
use App\Models\MstCurrency;
use App\Models\MstCountries;
use App\Models\MstReferral;
use App\Models\TrcSaldo;
use App\Models\TrcInvBank;
use App\Models\TrcTransactionSaldo;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DataTables;
use DB;
use App\Utility;
use Dotenv\Exception\ValidationException;

class MstInvestorController extends Controller
{
    const MODULE_NAME = 'Investor';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('transaction.investor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $model = new MstInvestor();

        $countries = DB::table('mst_countries')->get();

        $bank = MstBank::where('active', '1')
            ->where('is_deleted', '0')
            ->where('card_type', 'D')
            ->pluck('name', 'id')
            ->all();

        $currency = MstCurrency::pluck('currency', 'code')->all();

        return view('transaction.investor.create', compact(['model', 'countries', 'bank', 'currency']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $request->validate([
            'full_name' => 'required',
            'username' => 'required|string|unique:mst_investor,username|min:6',
            'password' => 'required|required_with:password_confirmation|string|confirmed',
            'password_confirmation' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'ktp_number' => 'required|string',
            'dummy' => 'required'
            // 'username'      => 'required|string|unique:mst_investor,username,1,is_deleted',
            // 'birth_place'   => 'required|string',
            // 'birth_date'    => 'required',
            // 'address'       => 'required',
            // 'country'       => 'required',
            // 'zip_code'       => 'required',
            // 'province'       => 'required',
            // 'regency'       => 'required',
            // 'district'       => 'required',
            // 'villages_id'       => 'required',
            // 'phone'       => 'required|string',
            // 'photo'       => 'required|file|image|mimes:jpg,jpeg,png|max:5048',
            // 'ktp_photo'       => 'required|file|image|mimes:jpg,jpeg,png|max:5048',
            // 'npwp_photo'       => 'required|file|image|mimes:jpg,jpeg,png|max:5048',
            // 'npwp_number'       => 'required|string',
            // 'bank_id'       => 'required',
            // 'acc_name'       => 'required',
            // 'acc_number'       => 'required',
            // 'currency_code'       => 'required',
        ]);

        $userId = Auth::user()->id;
        DB::beginTransaction();
        $code = MstInvestor::getCode();
        try {
            $data = [
                'code' => $code,
                'username' => $request->username,
                'password' => sha1($request->password),
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date != null ? date('Y-m-d', strtotime($request->birth_date)) : null,
                'birth_place' => $request->birth_place,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'email' => $request->email,
                'phone' => $request->phone,
                'ktp_number' => $request->ktp_number,
                'npwp_number' => $request->npwp_number,
                'currency_code' => $request->currency_code,
                'countries_id' => $request->country,
                'active' => 1,
                'created_at_emp' => date('Y-m-d'),
                'created_by_emp' => $userId,
                'is_dummy' => $request->dummy,
                'register_on' => 'BK'
            ];

            //Photo Investor
            if ($img = $request->file('photo')) {
                $img_name = time() . '.' . $img->extension();
                $img->storeAs('investor', $img_name, 'public-img');
                $data = array_merge($data, ['photo' => $img_name]);
            }

            //Photo KTP
            if ($img = $request->file('ktp_photo')) {
                $img_name = time() . '.' . $img->extension();
                $img->storeAs('foto_berkas', $img_name, 'public-foto-berkas');
                $data = array_merge($data, ['ktp_photo' => $img_name]);
            }

            //Photo NPWP
            if ($img = $request->file('npwp_photo')) {
                $img_name = time() . '.' . $img->extension();
                $img->storeAs('foto_berkas', $img_name, 'public-foto-berkas');
                $data = array_merge($data, ['npwp_photo' => $img_name]);
            }

            if ($model = MstInvestor::create($data)) {
                $bank = [
                    'investor_id' => $model->id,
                    'bank_id' => $request->bank_id,
                    'account_holder_name' => $request->acc_name,
                    'account_number' => $request->acc_number,
                    'active' => '1'
                ];
                TrcInvBank::create($bank);
                \UserLogActivity::addLog('Create ' . self::MODULE_NAME . ' ID #' . $model->id . ' Successfully');
                DB::commit();
                return redirect()->route('investor.show', base64_encode($model->id))->with('success', 'Investor created');
            } else {
                DB::rollback();
            }
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
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
        if (!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $model = MstInvestor::findOrFail($id);

        $countries = MstCountries::pluck('name', 'id')->all();

        $currency = MstCurrency::pluck('currency', 'code')->all();

        $bank_ib = MstBank::where('active', 1)
            ->where('is_deleted', 0)
            ->where('card_type', 'C')
            ->pluck('name', 'id')
            ->all();

        $bank = MstBank::where('active', '1')
            ->where('is_deleted', '0')
            ->where('card_type', 'D')
            ->pluck('name', 'id')
            ->all();

        $invbank = TrcInvBank::where('investor_id', $id)
            ->where('active', '1')
            ->where('is_deleted', '0')
            ->orderBy('id')
            ->first();

        $trcinvbank = new TrcInvBank();

        return view('transaction.investor.detail', compact([
            'model',
            'countries',
            'bank',
            'countries',
            'currency',
            // 'provinces', 
            // 'regencies', 
            // 'districts', 
            // 'villages', 
            // 'uti', 
            // 'address',
            'invbank',
            'trcinvbank',
            'bank_ib'
        ]));
    }

    public function profilePane($id)
    {
        $model = MstInvestor::findOrFail($id);

        $countries = DB::table('mst_countries')->get();

        $bank = MstBank::where('active', '1')
            ->where('is_deleted', '0')
            ->where('card_type', 'D')
            ->pluck('name', 'id')
            ->all();

        $uti = new Utility();
        $address = $uti->getDetailAddress($model->villages_id);
        $countries = DB::table('mst_countries')->get();
        $provinces = DB::table('mst_provinces')->where('countries_id', $address['country_id'])->get();
        $regencies = DB::table('mst_regencies')->where('provinces_id', $address['province_id'])->get();
        $districts = DB::table('mst_districts')->where('regencies_id', $address['regency_id'])->get();
        $villages = DB::table('mst_villages')->where('districts_id', $address['district_id'])->get();

        $invbank = TrcInvBank::where('investor_id', $id)
            ->where('active', '1')
            ->where('is_deleted', '0')
            ->orderBy('id')
            ->first();

        return view('transaction.investor.tab.profile', compact([
            'model',
            'countries',
            'bank',
            'countries',
            'provinces',
            'regencies',
            'districts',
            'villages',
            'uti',
            'address',
            'invbank'
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
        if (!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $a = ['mst_contact', 'mst_notification', 'trc_balance', 'trc_cart', 'trc_inv_bank'];
        $b = ['investor_id', 'investor_id', 'investor_id', 'investor_id', 'investor_id'];


        if (!$this->isAllowedToUpdate($a, $b, $id))
            abort(401, 'Table has related.');

        $model = MstInvestor::findOrFail($id);
        $uti = new Utility();
        $address = $uti->getDetailAddress($model->village_id);
        $countries = DB::table('mst_countries')->get();
        $provinces = DB::table('mst_provinces')->where('countries_id', $address['country_id'])->get();
        $regencies = DB::table('mst_regencies')->where('provinces_id', $address['province_id'])->get();
        $districts = DB::table('mst_districts')->where('regencies_id', $address['regency_id'])->get();
        $villages = DB::table('mst_villages')->where('districts_id', $address['district_id'])->get();


        $page = MstMember::where('active', '1')
            ->where('is_deleted', '0')
            ->pluck('member', 'id')
            ->all();

        return view(
            'transaction.investor.update',
            compact([
                'model',
                'countries',
                'provinces',
                'regencies',
                'districts',
                'villages',
                'uti',
                'address', 'page'
            ])
        );
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
        if (!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(401, 'Unauthorized action.');

        $id = base64_decode($id);

        $validate = $this->validate($request, [
            // 'member_id'     => 'required',
            'username' => 'required|string|unique:mst_investor,username,' . $id . ',id,is_deleted,0',
            'password' => 'nullable|required_with:password_confirmation|string|confirmed',
            'full_name' => 'required',
            'gender' => 'required',
            'birth_place' => 'required|string',
            'birth_date' => 'required',
            'countries_id' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
            'email' => 'required|string',
            'phone' => 'required|string',
            'ktp_number' => 'required|string',
            'npwp_number' => 'required|string',
            'active' => 'required',
            'currency_code' => 'required'
            // 'province'       => 'required',
            // 'regency'       => 'required',
            // 'district'       => 'required',
            // 'villages_id'       => 'required',

            // 'bank_id'       => 'required',
            // 'acc_name'       => 'required',
            // 'acc_number'       => 'required'

        ]);

        if ($validate) {
            $userId = Auth::user()->id;
            $current_date = date('Y-m-d H:i:s');
            $old_image_inv = '';
            $old_image_ktp = '';
            $old_image_npwp = '';
            $old_password = '';


            $model = MstInvestor::findOrFail($id);

            $data = [
                'username'  => $request->username,
                'full_name' => $request->full_name,
                'gender'  => $request->gender,
                'birth_date'  => date('Y-m-d', strtotime($request->birth_date)),
                'birth_place'  => $request->birth_place,
                'address'  => $request->address,
                'countries_id'  => $request->countries_id,
                'zip_code'  => $request->zip_code,
                'email'  => $request->email,
                'phone'  => $request->phone,
                'ktp_number'  => $request->ktp_number,
                'npwp_number'  => $request->npwp_number,
                'currency_code'     => $request->currency_code,
                'active'    => $request->active,
                'updated_at_emp' => $current_date,
                'updated_by_emp' => $userId
            ];

            $image_inv = $request->file('photo');
            if ($image_inv != null) {
                $filename_inv = time() . $image_inv->getClientOriginalName();
                $path = base_path() . '/public/images/investor/';
                $image_inv->move($path, $filename_inv);

                $data = array_merge($data, ['photo' => $filename_inv]);

                if ($model->photo != null)
                    $old_image_inv = $model->photo;
            }

            $image_ktp = $request->file('ktp_photo');
            if ($image_ktp != null) {
                $filename_ktp = time() . $image_ktp->getClientOriginalName();
                $path_ktp = base_path() . '/public/images/investor/foto_berkas/';
                $image_ktp->move($path_ktp, $filename_ktp);

                $data = array_merge($data, ['ktp_photo' => $filename_ktp]);

                if ($model->ktp_photo != null)
                    $old_image_ktp = $model->ktp_photo;
            }

            $image_npwp = $request->file('npwp_photo');
            if ($image_npwp != null) {

                $filename_npwp = time() . $image_npwp->getClientOriginalName();
                $path_npwp = base_path() . '/public/images/investor/foto_berkas/';
                $image_npwp->move($path_npwp, $filename_npwp);

                $data = array_merge($data, ['npwp_photo' => $filename_npwp]);

                if ($model->npwp_photo != null)
                    $old_image_npwp = $model->npwp_photo;
            }

            $password1 = $request->password;
            if ($password1 != null) {
                $data = array_merge($data, ['password'  => sha1($request->password)]);
            }

            if ($model->update($data)) {
                if ($old_image_inv != null)
                    unlink(base_path() . '/public/images/investor/' . $old_image_inv);

                if ($old_image_ktp != null)
                    unlink(base_path() . '/public/images/investor/foto_berkas/' . $old_image_ktp);

                if ($old_image_npwp != null)
                    unlink(base_path() . '/public/images/investor/foto_berkas/' . $old_image_npwp);

                \UserLogActivity::addLog('Update ' . self::MODULE_NAME . ' ID #' . $model->id . ' Successfully');
                return redirect('transaction/investor/' . base64_encode($model->id))->with('success', 'Investor data updated');
            } else
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
        if (!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_investor set 
            deleted_at_emp='" . $deleted_date . "',
            deleted_by_emp = " . $deleted_by . ",
            is_deleted = 1
            where id = " . $id . "");

        \UserLogActivity::addLog('Delete ' . self::MODULE_NAME . ' ID #' . $id . ' Successfully');
    }


    public function delete($id)
    {
        if (!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_investor set 
            deleted_at = '" . $deleted_date . "',
            deleted_by = " . $deleted_by . ",
            is_deleted = 1
            where id = " . $id . "");

        if ($delete) {
            \UserLogActivity::addLog('Delete ' . self::MODULE_NAME . ' ID #' . $id . ' Successfully');
            return redirect('transaction/investor')->with('success', 'Deleted');
        } else
            return redirect('transaction/investor/show/' . $id)->with('error', 'Failed');
    }

    public function dataTable()
    {
        $model = MstInvestor::where('is_deleted', 0)
            ->where('is_completed', '=', '1')
            ->orderBy('id', 'desc');

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function ($model) {
                return view('transaction.investor.action', [
                    'model' => $model,
                    'url_show' => route('investor.show', base64_encode($model->id)),
                    'url_destroy' => route('investor.destroy', base64_encode($model->id))
                ]);
            })
            ->editColumn('full_name', function ($model) {
                return $model->is_dummy == 1 ? $model->full_name . ' <span class="label label-default">DUMMY</span>' : $model->full_name;
            })
            ->editColumn('active', function ($model) {
                return $model->active == 1 ? 'Active' : 'Inactive';
            })

            ->editColumn('created_at_emp', function ($model) {
                return date('d-M-Y', strtotime($model->created_at_emp));
            })

            ->editColumn('is_completed', function ($model) {
                if ($model->is_completed == '1') {
                    $status = "Completed";
                } else {
                    $status = "Activation";
                }
                return $status;
            })

            ->editColumn('member_id', function ($model) {
                $balance = DB::select('select f_get_balance(' . $model->id . ') as balance');

                foreach ($balance as $key) {
                    # code...
                    $blc_inv = $key->balance;
                }

                return "Rp. " . number_format($blc_inv);
            })

            ->editColumn('created_at_investor', function ($model) {
                $investment = DB::select('select f_investor_investment(' . $model->id . ') as investment');

                foreach ($investment as $key) {
                    # code...
                    $investment_inv = $key->investment;
                }

                return "Rp. " . number_format($investment_inv);
            })

            ->editColumn('updated_at_currency', function ($model) {
                $investment = DB::select('select f_investor_total_asset(' . $model->id . ') as investment');

                foreach ($investment as $key) {
                    # code...
                    $investment_inv = $key->investment;
                }

                return $investment_inv . " Asset";
            })

            ->editColumn('register_on', function ($model) {
                $register = "<i>" . $model->register_on . "</i>";

                return $register;
            })

            ->editColumn('created_by_emp', function ($model) {
                $uti = new utility();
                $user = '';
                if ($model->created_by_emp)
                    $user = $uti->getUser($model->created_by_emp);

                return $user;
            })
            // ->addIndexColumn()
            ->rawColumns(['full_name', 'action', 'active', 'member_id', 'created_at_investor', 'updated_at_currency', 'created_at_emp', 'status', 'register_on'])
            ->make(true);
    }

    public function dataTable2()
    {
        $model = MstInvestor::where('is_deleted', 0)
            ->where('is_completed', '=', '0')
            ->orderBy('id', 'desc');

        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function ($model) {
                return view('transaction.investor.action', [
                    'model' => $model,
                    'url_show' => route('investor.show', base64_encode($model->id)),
                    'url_destroy' => route('investor.destroy', base64_encode($model->id))
                ]);
            })
            ->editColumn('active', function ($model) {
                return $model->active == 1 ? 'Active' : 'Inactive';
            })
            ->editColumn('created_at_emp', function ($model) {
                return date('d-M-Y', strtotime($model->created_at_emp));
            })

            ->editColumn('is_completed', function ($model) {
                if ($model->is_completed == '1') {
                    $status = "Completed";
                } else {
                    $status = "Activation";
                }
                return $status;
            })

            ->editColumn('register_on', function ($model) {
                $register = "<i>" . $model->register_on . "</i>";

                return $register;
            })

            ->editColumn('member_id', function ($model) {
                $balance = DB::select('select f_get_balance(' . $model->id . ') as balance');

                foreach ($balance as $key) {
                    # code...
                    $blc_inv = $key->balance;
                }

                return "Rp. " . number_format($blc_inv);
            })

            ->editColumn('created_at_investor', function ($model) {
                $investment = DB::select('select f_investor_investment(' . $model->id . ') as investment');

                foreach ($investment as $key) {
                    # code...
                    $investment_inv = $key->investment;
                }

                return "Rp. " . number_format($investment_inv);
            })

            ->editColumn('updated_at_currency', function ($model) {
                $investment = DB::select('select f_investor_total_asset(' . $model->id . ') as investment');

                foreach ($investment as $key) {
                    # code...
                    $investment_inv = $key->investment;
                }

                return $investment_inv . " Asset";
            })

            ->editColumn('created_by_emp', function ($model) {
                $uti = new utility();
                $user = '';
                if ($model->created_by_emp)
                    $user = $uti->getUser($model->created_by_emp);

                return $user;
            })
            // ->addIndexColumn()
            ->rawColumns(['action', 'active', 'member_id', 'created_at_investor', 'updated_at_currency', 'created_at_emp', 'is_completed', 'register_on'])
            ->make(true);
    }

    public function topupform($id)
    {
        return view('transaction.investor.topup', compact($id));
    }

    public function randomInvestment($id) //class asset id
    {
        $model = new MstInvestor;
        $response = $model->randomInvest($id);
        echo json_encode($response);
    }

    public function referral($id)
    {
        $model = MstInvestor::findOrFail($id);
        return view('transaction.investor.tab.referral', compact('model'));
    }

    public function referralParent($id)
    {
        $model = MstReferral::select(['mst_investor.full_name','mst_referral.used_date','mst_referral.amount'])
            ->join('mst_investor','mst_referral.ref_investor_id','=','mst_investor.id')
            ->where([
                'investor_id' => $id
            ])
            ->get();
        return DataTables::of($model)
            ->editColumn('used_date', function($model){
                return $model->used_date ? date('d-m-Y H:i', strtotime($model->used_date)) : null;
            })
            ->editColumn('amount', function($model){
                return $model->amount ? number_format($model->amount, 0, ',', '.') : null;
            })
            ->rawColumns(['used_date','amount'])
            ->addIndexColumn()
            ->make(true);
    }

    public function referralChild($id)
    {
        $model = MstReferral::select(['mst_investor.full_name','mst_referral.used_date','mst_referral.amount'])
            ->join('mst_investor','mst_referral.investor_id','=','mst_investor.id')
            ->where([
                'ref_investor_id' => $id
            ])
            ->get();
        return DataTables::of($model)
            ->editColumn('used_date', function($model){
                return $model->used_date ? date('d-m-Y H:i', strtotime($model->used_date)) : null;
            })
            ->editColumn('amount', function($model){
                return $model->amount ? number_format($model->amount, 0, ',', '.') : null;
            })
            ->rawColumns(['used_date','amount'])
            ->addIndexColumn()
            ->make(true);
    }
}
