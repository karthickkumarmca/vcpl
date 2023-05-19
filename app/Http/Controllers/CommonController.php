<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Admin;
use Session;

class CommonController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function dashboard(Request $request)
	{
		$user_type = Session::get('user_type');
		if($user_type==3){
			 return redirect(url(route('create-cement-movement')));
		}
		$value =$request->session()->all();
		// print_r($value);exit;
		// echo Hash::make('Vcpladmin@2023');exit;
		$customers_count   = Customer::getdashboardcount();
		$user_count       = User::getdashboardcount();
		$data['show_dashboard_list']    = 1;
		$data['customers_count']=$customers_count;
		$data['user_count']=$user_count;
		return view('admin.dashboard_new',$data);
	}
}
