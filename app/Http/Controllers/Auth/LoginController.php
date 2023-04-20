<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        // echo Hash::make($request->password);exit;
        // dd($request->all());
        $user = User::where(['email' => $request->email])->whereIn('user_type', array(1,2))->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (Auth::guard()) {
                    if ($user->status != 1) {
                        throw ValidationException::withMessages([
                            'email' => 'Account is not Active. Contact Admin',
                        ]);
                    } else {
                        auth()->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1]);
                        return redirect()->route('home');
                    }
                } else {
                    throw ValidationException::withMessages([
                        'email' => 'Something went wrong.'
                    ]);
                }
            } else {
                throw ValidationException::withMessages([
                    'email' => 'Invalid Credentials.'
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                'email' => 'Invalid Credentials.'
            ]);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user) {
            session(['user_role' => config("general_settings.user_type.{$user->user_type}")]);
            session(['user_type' => $user->user_type]);
            session(['user_id' => $user->id]);
        }
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $validation = array(
            $this->username() => 'required|string',
            'password' => 'required|string',
        );
       
        $request->validate($validation);
    }
}
