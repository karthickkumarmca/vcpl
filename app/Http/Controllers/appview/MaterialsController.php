<?php

namespace App\Http\Controllers\appview;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Illuminate\Validation\Validator;
use Session;

class MaterialsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   

    public function cement_movement(Request $request){

        return view('appview.cement_movement');
    }
    public function shop_movement(Request $request){

        return view('appview.shop_movement');
    }
    public function lorry_movement(Request $request){

        return view('appview.lorry_movement');
    }
    public function labour_movement(Request $request){

        return view('appview.labour_movement');
    }
    public function workout_movement(Request $request){

        return view('appview.workout_movement');
    }
    public function centering_movement(Request $request){

        return view('appview.centering_movement');
    }
    public function tools_movement(Request $request){

        return view('appview.tools_movement');
    }
   
}
