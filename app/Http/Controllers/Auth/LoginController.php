<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Siteinfo;
use App\Models\Roles;
use App\Models\Staffdetails;
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
    protected $redirectTo = '/dashboard';

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

        $fields = [
            'users.*',
            'roles.master as master_access'
        ];
        $user = User::select($fields)
        ->leftjoin('roles','roles.id','users.role_id')
        ->where(['user_name' => $request->email])->whereIn('user_type', array(1,2))->first();
        // print_r($user);exit;
       
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (Auth::guard()) {
                    if ($user->status != 1) {
                        throw ValidationException::withMessages([
                            'email' => 'Account is not Active. Contact Admin',
                        ]);
                    } else {

                        if($user->user_type !=1){
                            $Staffdetails = Staffdetails::select('status')->where(['email' => $request->email])->first();
                            if($Staffdetails){

                                if ($Staffdetails->status != 1) {

                                     throw ValidationException::withMessages([
                                        'email' => ' your account is inactive, please contact admin'
                                    ]);
                                }
                               
                            }
                        }
                        
                        auth()->attempt(['user_name' => $request->email, 'password' => $request->password, 'status' => 1]);
                        $Staffdetails = Staffdetails::select('id','name','user_groups_ids')->where(['uuid' => $user->uuid])->first();
                       
                        if($Staffdetails){
                            $staff_id     = $Staffdetails->id;
                            $siteData = Siteinfo::select('id','site_name')->where('site_engineer_id',$staff_id)->get()->toArray();
                            if(count($siteData)!=1){
                                throw ValidationException::withMessages([
                                        'email' => ' User mapped with wrongly'
                                ]);
                            }
                            else{
                                if($Staffdetails->user_groups_ids==4){
                                    return redirect()->route('create-cement-movement');
                                }
                                else{
                                    return redirect()->route('dashboard');
                                }
                            }
                        }
                        else{
                            return redirect()->route('dashboard');
                        }
                       
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

            $role_access = '';
            $name        = '';
            $username    = !empty($user->user_name)?$user->user_name:'';
            if($user->user_type==1){
                $role_access = config("roles.".config("general_settings.user_type.1"));
                $name = $user->name;
                // if($request->email=='superadmin'){
                //     $user->user_type = 3;
                // }
            }
            else if($user->user_type==2){
                if($user->role_id>0){
                    $roles = Roles::where('id',$user->role_id)->first();
                    if($roles){
                        if($roles->master!=''){
                            $master_access =   explode(",",$roles->master);
                             // echo "<pre>"; print_r($master_access);
                            $role_access = config("roles.".config("general_settings.user_type.1"));
                            foreach($role_access as $key=>$ur){
                                if(in_array($key,$master_access)){
                                    $role_access[$key] = $ur;

                                }
                                else{
                                    if(is_array($ur)){
                                        $mage = str_replace("_access", "", $key);
                                        if(!in_array($mage,$master_access)){
                                            foreach($ur as $key1=>$yu){
                                                $role_access[$key][$key1] = 0;
                                            }
                                        }
                                        else{
                                            $role_access[$key] = $ur;
                                        }
                                        
                                    }
                                    else{
                                        $role_access[$key] = 0;
                                    }
                                    
                                }
                                
                            }
                        }
                    }
                }
            }
            if(empty($role_access)){
                $role_access = config("roles.".config("general_settings.user_type.1"));
                $master_access =   [];
                foreach($role_access as $key=>$ur){
                    if(in_array($key,$master_access)){
                        $role_access[$key] = $ur;

                    }
                    else{
                        if(is_array($ur)){
                            $mage = str_replace("_access", "", $key);
                            if(!in_array($mage,$master_access)){
                                foreach($ur as $key1=>$yu){
                                    $role_access[$key][$key1] = 0;
                                }
                            }
                            else{
                                $role_access[$key] = $ur;
                            }
                            
                        }
                        else{
                            $role_access[$key] = 0;
                        }
                    }
                }
            }
            $site_ids       = 0;
            $Staffdetails   = Staffdetails::select('id','name','user_groups_ids')->where(['uuid' => $user->uuid])->first();
            if($Staffdetails){
                $name         = $Staffdetails->name;
                $staff_id     = $Staffdetails->id;
                if($Staffdetails->user_groups_ids==4){
                    $siteData = Siteinfo::select('id','site_name')->where('site_engineer_id',$staff_id)->get()->toArray();
                    if(count($siteData)==1){
                        $site_ids = isset($siteData[0]['id'])?$siteData[0]['id']:0;
                    }
                    $role_access = config("roles.".config("general_settings.user_type.1"));
                    $master_access =   [];
                    foreach($role_access as $key=>$ur){
                        if(in_array($key,$master_access)){
                            $role_access[$key] = $ur;

                        }
                        else{
                            if(is_array($ur)){
                                $mage = str_replace("_access", "", $key);
                                if(!in_array($mage,$master_access)){
                                    foreach($ur as $key1=>$yu){
                                        $role_access[$key][$key1] = 0;
                                    }
                                }
                                else{
                                    $role_access[$key] = $ur;
                                }
                                
                            }
                            else{
                                $role_access[$key] = 0;
                            }
                        }
                    }
                    $user->user_type = 3;
                }
            }
            // echo $user->user_type;exit;
            // echo "qqq<pre>";print_r($role_access);exit;
            session(['user_role' => config("general_settings.user_type.{$user->user_type}")]);
            session(['user_type' => $user->user_type]);
            session(['user_id' => $user->id]);
            session(['role_access' => $role_access]);
            session(['name' => $name]);
            session(['username' => $username]); 
            session(['site_id' => $site_ids]); 
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
