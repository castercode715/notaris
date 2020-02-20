<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MstEmployee;
use Session;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'site';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function guard()
    // {
    //     return Auth::guard('employee');
    // }

    // public function username()
    // {
    //     return 'username';
    // }
    protected function credentials(Request $request)
    {
        $field = $this->field($request);

        return [
            $field => $request->get($this->username()),
            'password' => $request->get('password'),
            'active' => MstEmployee::ACTIVE
        ];
    }

    public function field(Request $request)
    {
        $email = $this->username();

        return filter_var($request->get($email), FILTER_VALIDATE_EMAIL) ? $email : 'username';
    }

    protected function validateLogin(Request $request)
    {
        $field = $this->field($request);

        $message = ['{ $this->username() }.exists'=>'The account you are trying to login is not activated or it has been disabled.'];

        $this->validate($request, [
            $this->username() => "required|exists:mst_employee,{$field},active,".MstEmployee::ACTIVE,
            'password' => 'required',
        ], $message);
    }

    public function showLoginForm()
    {
        return view('site.login');
    }

    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'username'  => 'required',
    //         'password'  => 'required'
    //     ]);

    //     // $user = [
    //     //     'username'  => $request->get('username'),
    //     //     'password'  => $request->get('password'),
    //     //     'active'    => SecEmployee::ACTIVE,
    //     // ];

    //     // $users = DB::select('select * from sec_employee where username="'.$request->username.'" and password = "'.sha1($request->password).'"');
    //     $users = DB::table('sec_employee')
    //         ->where('username', '=', $request->username)
    //         ->where('password', '=', sha1($request->password))
    //         ->where('active', '=', 1)
    //         ->get();

    //     // var_dump($users);
    //     // die();

    //     //   echo"<pre>";
    //     //     print_r($users);
    //     //   echo"</pre>";

    //     //   die();

    //     if(count($users))
    //     {
    //         Session::put('id', $users[0]->sec_employee_id);
    //         return redirect('site');
    //     }
    //     else
    //         return back()->with('error', 'Failed to login');
        
    //     // if(Auth::attempt($user)){
    //     //     dd($user);
    //     //     Session::put('id', $user->sec_role_id);
            
    //     //     return redirect($this->redirectTo);
    //     // }
    //     // else
    //     //     return back()->with('error', 'Failed to login');
    // }

    // public function logout()
    // {
    //     // Auth::logout();
    //     Session::forget('id');
    //     Session::flush();

    //     return redirect('login');
    // }

    public function logout(Request $request)
    {
        $setNull = DB::table('mst_employee')->where('id', Auth::id())
                    ->update(['is_notaris' => NULL ]);

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
