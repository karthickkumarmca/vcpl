<?php

namespace App\Http\Controllers\appview;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Illuminate\Validation\Validator;
use Session;
use App\Models\appview\Cement_transactions;
use App\Models\appview\Materials_stock;
use App\Models\Vehicle_materials;
use App\Models\Staffdetails; 
use App\Models\Siteinfo;
use App\Models\Labour_categories;
use App\Models\appview\Labour_transactions;
use App\Models\appview\Centering_transactions;
use App\Models\appview\Tools_transactions;


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

        if ($request->has('request_type')) {
            $searchField = [
                'status'    => 'cement_transactions.status',
            ];
            $sortField   = [
                'date_created' => 'cement_transactions.created_at'
            ];
            $search_filter = [];
            $sort = [
                'field' => 'cement_transactions.created_at',
                'order' => 'desc'
            ];
            $page = config('pagination.page');
            if ($request->has('page')) {
                $page = $request->page;
            }

            $offset = config('pagination.offset');
            if ($request->has('offset')) {
                $offset = $request->offset;
            }

            if ($request->has('sort')) {
                $name = $request->sort['field'];
                if (isset($sortField[$name])) {
                    $sort['field'] = $sortField[$name];
                    $sort['order'] = $request->sort['order'];
                }
            }

            $filters = $request->get('search') ? $request->get('search') : [];

            foreach ($filters as $search_field => $search_value) {
                $search_value = strip_tags($search_value); //Sanitization
                $table_field = $searchField[$search_field];
                if (in_array($search_field, ["status"])) {
                    array_push($search_filter, [$table_field, '=', $search_value]);
                } else {
                    array_push($search_filter, [$table_field, 'LIKE', '%' . addslashes($search_value) . '%']);
                }
            }

            $records = Cement_transactions::get_materials($page, $offset, $sort, $search_filter);
            //print_r($records);exit;

            if (!empty($records['records'])) {
                $statusCode = '200';
                $message    = "Data are retrieved Successfully";
                $data       = $records;
            } else {
                $statusCode = '400';
                $message    = "No data info found";
                $data       = $records;
            }

            $response = response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => (object)[]
            ], $statusCode);

            return $response;
        }
        else{

            $site_id = Session::get('site_id');
            $stock   = 0;
            $getdata = Materials_stock::where('materials_category_id',1)->where('site_id',$site_id)->get()->toArray();
            if(count($getdata)>0){
                $stock = isset($getdata[0]['stock'])?$getdata[0]['stock']:0;
            }
           

            $site_id = Session::get('site_id');
            $search1[]          = ['id','!=', $site_id];
            $search1['status']  = 1;
            // print_r($search1);exit;
            $fields1            = ['id','site_name'];
            $siteinfo           = Siteinfo::getAll($fields1,$search1);

            $search1 = ['status' => 1];
            $fields1 = ['id','vehicle_name'];
            $Vehicle_materials = Vehicle_materials::getAll($fields1,$search1);
            return view('appview.cement_movement',compact('siteinfo','Vehicle_materials','stock'));
        }

        
    }
    public function cement_store(Request $request)
    {
       
        if($request->get('selected_tab')==2){

            $fieldValidation = [
            'bill_number'         => ['required','min:2','max:15','unique:cement_transactions,bill_number' ],
            ];
            $fieldValidation['rquantity']       = ['required'];
            $fieldValidation['vehicle_id']      = ['required'];
        }
        else if($request->get('selected_tab')==3){

            $fieldValidation['bags']         = ['required'];
            $fieldValidation['purpose']      = ['required'];
        }
        else{
            $fieldValidation = [
            'transfer_slip_number'         => ['required','min:2','max:15','unique:cement_transactions,bill_number' ],
            ];
            $fieldValidation['quantity']                = ['required'];
            $fieldValidation['transfer_slip_number']    = ['required'];
            $fieldValidation['vechile_number']          = ['required'];
        }
            
           

        $errorMessages    = [
            'rquantity.required'             => "The quantity field is required.",
            'quantity.required'              => "The quantity field is required.",
        ];

        $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator);
        }
        if(in_array($request->selected_tab,[1,3])){

            $site_id = Session::get('site_id');

            $getdata = Materials_stock::where('materials_category_id',1)->where('site_id',$site_id)->get()->toArray();
            if(count($getdata)==0){
                Session::flash('message', 'your issue value is more then opening balance,so transaction incomplete');
                Session::flash('class', 'error');
                return redirect('appview/cement-movement/create'); 
            }
            $stock = isset($getdata[0]['stock'])?$getdata[0]['stock']:0;

            if($stock<$request->quantity){
                Session::flash('message', 'your issue value is more then opening balance,so transaction incomplete');
                Session::flash('class', 'error');
                return redirect('appview/cement-movement/create'); 
            }
        }
       
       
        $request['created_at']=date('Y-m-d H:i:s');
        $response   = Cement_transactions::storeRecords($request);

        Session::flash('message', 'Cement recorded has been successfully');
        Session::flash('class', 'success');

        return redirect('appview/cement-movement/create'); 

        
    }
    public function shop_movement(Request $request){

        return view('appview.shop_movement');
    }
    public function lorry_movement(Request $request){

        return view('appview.lorry_movement');
    }
    public function labour_movement(Request $request){
        $search           = ['status' => 1,'user_groups_ids'=>6];
        $fields           = ['id','name','user_name'];
        $Staffgroups      = Staffdetails::getAll($fields,$search);
        $searchLabour     = ['status' => 1];
        $fieldsLabour     = ['id','category_name'];
        $LabourCategories = Labour_categories::getAll($fieldsLabour,$searchLabour);
        $data             = ["subcontractors"=>$Staffgroups,"labour_categories"=>$LabourCategories];
        return view('appview.labour_movement',$data);
    }
    public function workout_movement(Request $request){

        return view('appview.workout_movement');
    }
    public function centering_movement(Request $request){

        if ($request->has('request_type')) {
            $searchField = [
                'status'    => 'centering_transactions.status',
            ];
            $sortField   = [
                'date_created' => 'centering_transactions.created_at'
            ];
            $search_filter = [];
            $sort = [
                'field' => 'centering_transactions.created_at',
                'order' => 'desc'
            ];
            $page = config('pagination.page');
            if ($request->has('page')) {
                $page = $request->page;
            }

            $offset = config('pagination.offset');
            if ($request->has('offset')) {
                $offset = $request->offset;
            }

            if ($request->has('sort')) {
                $name = $request->sort['field'];
                if (isset($sortField[$name])) {
                    $sort['field'] = $sortField[$name];
                    $sort['order'] = $request->sort['order'];
                }
            }

            $filters = $request->get('search') ? $request->get('search') : [];

            foreach ($filters as $search_field => $search_value) {
                $search_value = strip_tags($search_value); //Sanitization
                $table_field = $searchField[$search_field];
                if (in_array($search_field, ["status"])) {
                    array_push($search_filter, [$table_field, '=', $search_value]);
                } else {
                    array_push($search_filter, [$table_field, 'LIKE', '%' . addslashes($search_value) . '%']);
                }
            }

            $records = Centering_transactions::get_materials($page, $offset, $sort, $search_filter);
            //print_r($records);exit;

            if (!empty($records['records'])) {
                $statusCode = '200';
                $message    = "Data are retrieved Successfully";
                $data       = $records;
            } else {
                $statusCode = '400';
                $message    = "No data info found";
                $data       = $records;
            }

            $response = response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => (object)[]
            ], $statusCode);

            return $response;
        }
        else{

            $site_id = Session::get('site_id');
            $stock   = 0;
            $getdata = Materials_stock::where('materials_category_id',2)->where('site_id',$site_id)->get()->toArray();
            if(count($getdata)>0){
                $stock = isset($getdata[0]['stock'])?$getdata[0]['stock']:0;
            }

            $site_id = Session::get('site_id');
            $search1[]          = ['id','!=', $site_id];
            $search1['status']  = 1;
            // print_r($search1);exit;
            $fields1            = ['id','site_name'];
            $siteinfo           = Siteinfo::getAll($fields1,$search1);

            $search1 = ['status' => 1];
            $fields1 = ['id','vehicle_name'];
            $Vehicle_materials = Vehicle_materials::getAll($fields1,$search1);
            return view('appview.centering_movement',compact('siteinfo','Vehicle_materials','stock'));
        }
       
    }
    public function centering_store(Request $request)
    {
          
        $fieldValidation['quantity']        = ['required'];
        $fieldValidation['vehicle_id']      = ['required'];
        $fieldValidation['site_id']         = ['required'];

        $errorMessages    = [
            'quantity.required'              => "The quantity field is required.",
        ];

        $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator);
        }

        $site_id = Session::get('site_id');

        $getdata = Materials_stock::where('materials_category_id',2)->where('site_id',$site_id)->get()->toArray();
        if(count($getdata)==0){
            Session::flash('message', 'your issue value is more then opening balance,so transaction incomplete');
            Session::flash('class', 'error');
            return redirect('appview/centering-movement/create'); 
        }
        $stock = isset($getdata[0]['stock'])?$getdata[0]['stock']:0;

        if($stock<$request->quantity){
            Session::flash('message', 'your issue value is more then opening balance,so transaction incomplete');
            Session::flash('class', 'error');
            return redirect('appview/centering-movement/create'); 
        }
        
       
        $request['created_at']=date('Y-m-d H:i:s');
        $response   = Centering_transactions::storeRecords($request);

        Session::flash('message', 'Cement recorded has been successfully');
        Session::flash('class', 'success');

        return redirect('appview/centering-movement/create'); 

        
    }
    public function tools_movement(Request $request){

        if ($request->has('request_type')) {
            $searchField = [
                'status'    => 'tools_transactions.status',
            ];
            $sortField   = [
                'date_created' => 'tools_transactions.created_at'
            ];
            $search_filter = [];
            $sort = [
                'field' => 'tools_transactions.created_at',
                'order' => 'desc'
            ];
            $page = config('pagination.page');
            if ($request->has('page')) {
                $page = $request->page;
            }

            $offset = config('pagination.offset');
            if ($request->has('offset')) {
                $offset = $request->offset;
            }

            if ($request->has('sort')) {
                $name = $request->sort['field'];
                if (isset($sortField[$name])) {
                    $sort['field'] = $sortField[$name];
                    $sort['order'] = $request->sort['order'];
                }
            }

            $filters = $request->get('search') ? $request->get('search') : [];

            foreach ($filters as $search_field => $search_value) {
                $search_value = strip_tags($search_value); //Sanitization
                $table_field = $searchField[$search_field];
                if (in_array($search_field, ["status"])) {
                    array_push($search_filter, [$table_field, '=', $search_value]);
                } else {
                    array_push($search_filter, [$table_field, 'LIKE', '%' . addslashes($search_value) . '%']);
                }
            }

            $records = Tools_transactions::get_materials($page, $offset, $sort, $search_filter);
            //print_r($records);exit;

            if (!empty($records['records'])) {
                $statusCode = '200';
                $message    = "Data are retrieved Successfully";
                $data       = $records;
            } else {
                $statusCode = '400';
                $message    = "No data info found";
                $data       = $records;
            }

            $response = response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => (object)[]
            ], $statusCode);

            return $response;
        }
        else{

            $site_id = Session::get('site_id');
            $stock   = 0;
            $getdata = Materials_stock::where('materials_category_id',6)->where('site_id',$site_id)->get()->toArray();
            if(count($getdata)>0){
                $stock = isset($getdata[0]['stock'])?$getdata[0]['stock']:0;
            }

            $site_id = Session::get('site_id');
            $search1[]          = ['id','!=', $site_id];
            $search1['status']  = 1;
            // print_r($search1);exit;
            $fields1            = ['id','site_name'];
            $siteinfo           = Siteinfo::getAll($fields1,$search1);

            $search1 = ['status' => 1];
            $fields1 = ['id','vehicle_name'];
            $Vehicle_materials = Vehicle_materials::getAll($fields1,$search1);
            return view('appview.tools_movement',compact('siteinfo','Vehicle_materials','stock'));
        }
    }
    public function tools_store(Request $request)
    {
          
        $fieldValidation['quantity']        = ['required'];
        $fieldValidation['vehicle_id']      = ['required'];
        $fieldValidation['site_id']         = ['required'];

        $errorMessages    = [
            'quantity.required'              => "The quantity field is required.",
        ];

        $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator);
        }

        $site_id = Session::get('site_id');

        $getdata = Materials_stock::where('materials_category_id',6)->where('site_id',$site_id)->get()->toArray();
        if(count($getdata)==0){
            Session::flash('message', 'your issue value is more then opening balance,so transaction incomplete');
            Session::flash('class', 'error');
            return redirect('appview/tools-movement/create'); 
        }
        $stock = isset($getdata[0]['stock'])?$getdata[0]['stock']:0;

        if($stock<$request->quantity){
            Session::flash('message', 'your issue value is more then opening balance,so transaction incomplete');
            Session::flash('class', 'error');
            return redirect('appview/tools-movement/create'); 
        }
        
       
        $request['created_at']=date('Y-m-d H:i:s');
        $response   = Tools_transactions::storeRecords($request);

        Session::flash('message', 'Tools recorded has been successfully');
        Session::flash('class', 'success');

        return redirect('appview/tools-movement/create'); 

        
    }
    public function task_movement(Request $request){

        return view('appview.task_movement');
    }

    public function labour_store(Request $request)
    {

        $fieldValidation['subcontractor_id']   = ['required',];
        //$fieldValidation['labour_category']    = ['required'];
        $fieldValidation['number_of_labour']   = ['required'];
        $fieldValidation['shift_id']           = ['required'];
        $errorMessages                         = [];

        $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator);
        }
       
        $response   = Labour_transactions::storeRecords($request);
        if($response>0){
            Session::flash('message', 'Labour recorded has been successfully');
            Session::flash('class', 'success');
        } else {
            Session::flash('message', 'Labour recorded has been successfully');
            Session::flash('class', 'success');
        }
        return redirect('appview/labour-movement/create'); 

        
    }
   
}
