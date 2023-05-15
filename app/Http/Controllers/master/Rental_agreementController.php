<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Property_name;
use App\Models\Rental_agreement;
use App\Models\Productdetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Session;

class Rental_agreementController extends Controller
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
    public function list(Request $request)
    {
         $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['rental_agreement_management']) || $rolesAccess['rental_agreement_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'tenant_name'        => 'rental_agreement.tenant_name',
                    'rental_amount'      => 'rental_agreement.rental_amount',
                    'contact_person_mobile_number'      => 'rental_agreement.contact_person_mobile_number',
                    'status'    => 'rental_agreement.status',
                ];
                $sortField   = [
                    'tenant_name'     => 'rental_agreement.tenant_name',
                    'status'  => 'rental_agreement.status',
                    'date_created' => 'rental_agreement.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'rental_agreement.id',
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

                $records = Rental_agreement::getlist($page, $offset, $sort, $search_filter);

                //print_r($records);exit;

                if (!empty($records['records'])) {
                    
                    $statusCode = '200';
                    $message    = "Product details are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No rental_agreement found";
                    $data       = $records;
                }

                $response = response()->json([
                    'message' => $message,
                    'data'    => $data,
                    'error'   => (object)[]
                ], $statusCode);

                return $response;
            } else {
                $statuses = [['value' => 1, 'label' => 'Active'], ['value' => 0, 'label' => 'In-Active']];

                $create_access = $view_access = $edit_access = $delete_access = $change_status_access = 0;
                if(isset($rolesAccess['rental_agreement_management_access'])){

                    $create_access          = $rolesAccess['rental_agreement_management_access']['create'];
                    $view_access            = $rolesAccess['rental_agreement_management_access']['view'];
                    $edit_access            = $rolesAccess['rental_agreement_management_access']['edit'];
                    $change_status_access   = $rolesAccess['rental_agreement_management_access']['change_status'];
                }

                return view('master.rental_agreement.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['rental_agreement_management_access']['create']) || $rolesAccess['rental_agreement_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','property_name'];
            $Property_name = Property_name::getAll($fields,$search);
            return view('master.rental_agreement.create',compact('Property_name'));
        }
    }
     public function getname(Request $request) {

        $values = array('category_name'=>'','ownership_name'=>'');

        $search = ['property_name.status' => 1,'property_name.id'=>$request->get('id')];
        $data = Property_name::getname($search);
        if(count($data)>0){
            $values = array('category_name'=>$data[0]['category_name'],'ownership_name'=>$data[0]['ownership_name']);
        }
        return $values;
        
    }
   
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['rental_agreement_management_access']['create']) || $rolesAccess['rental_agreement_management_access']['create']!=1){
            abort(403);
        } else {

           
            $fieldValidation['tenant_name']               = ['nullable','min:1','max:100'];
            $fieldValidation['aadhar_number']             = ['required','min:1','max:128'];
            $fieldValidation['rent_start_date']           = ['required'];
            $fieldValidation['rent_end_date']             = ['required'];
            $fieldValidation['rental_area']               = ['required'];
            $fieldValidation['rental_amount']             = ['required'];
            $fieldValidation['contact_person_name']       = ['required'];
            $fieldValidation['contact_person_mobile_number']             = ['required'];
            $fieldValidation['present_rental_rate']       = ['required'];
            $fieldValidation['advance_paid']              = ['required'];
            $fieldValidation['payment_mode']              = ['required'];
            $fieldValidation['property_id']               = ['required'];
            $fieldValidation['aadhar_img']                = ['nullable','mimes:png,jpg,jpeg,pdf|max:2048'];
            $fieldValidation['pan_img']                   = ['nullable','mimes:png,jpg,jpeg,pdf|max:2048'];
           

            $errorMessages    = [
                'property_id.required'             => "Select property name",
                'product_name.required'             => "Please enter the name",
                'product_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }
            if($request->file('aadhar_img')) {
                $file = $request->file('aadhar_img');
                $filename = time().'_'.$file->getClientOriginalName();
                $location = 'public/uploads/';
                $file->move($location,$filename);
                $request['aadhar_proof'] = $filename;
            }
            if($request->file('pan_img')) {
                $file = $request->file('pan_img');
                $filename = time().'_'.$file->getClientOriginalName();
                $location = 'public/uploads/';
                $file->move($location,$filename);
                $request['pan_proof'] = $filename;
            }
            

            if($request->has('rental_agreement_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Rental_agreement::storeRecords($request);
            }
            else{
                $response   = Rental_agreement::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/rental-agreement/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['rental_agreement_management_access']['view']) || $rolesAccess['rental_agreement_management_access']['view']!=1){
            abort(403);
        } else {

            
            $rental_agreement  = Rental_agreement::where(['uuid' => $id])->first();
            
            if ($rental_agreement) {

                $search = ['property_name.status' => 1,'property_name.id'=>$rental_agreement->property_id];
                $prdata = Property_name::getname($search);
                $rental_agreement->category_name = '';
                $rental_agreement->ownership_name = '';
                $rental_agreement->property_name = '';
                // echo $rental_agreement->property_id;exit;
                if(count($prdata)>0){
                    $rental_agreement->category_name = $prdata[0]['category_name'];
                    $rental_agreement->ownership_name = $prdata[0]['ownership_name'];
                    $rental_agreement->property_name = $prdata[0]['property_name'];
                }

                if($rental_agreement->aadhar_proof!=''){
                    $rental_agreement->aadhar_proof  = url('/').'/public/uploads/'.$rental_agreement->aadhar_proof;
                }
                if($rental_agreement->pan_proof!=''){
                    $rental_agreement->pan_proof   = url('/').'/public/uploads/'.$rental_agreement->pan_proof;
                }

                $data = [
                    'rental_agreement'   => $rental_agreement,
                ];

                return view('master.rental_agreement.view', $data);
            } else {
                $data = [
                    'message' => "Invalid rental_agreement"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['rental_agreement_management_access']['edit']) || $rolesAccess['rental_agreement_management_access']['edit']!=1){
            abort(403);
        } else {
            $rental_agreement  = Rental_agreement::where(['uuid' => $id])->first();
            if ($rental_agreement) {

                if($rental_agreement->rent_start_date!=''){
                     $rental_agreement->rent_start_date = date('m/d/Y',strtotime($rental_agreement->rent_start_date));
                }
                if($rental_agreement->rent_end_date!=''){
                    $rental_agreement->rent_end_date = date('m/d/Y',strtotime($rental_agreement->rent_end_date));
                }
                if($rental_agreement->aadhar_proof!=''){
                    $rental_agreement->aadhar_proof  = url('/').'/public/uploads/'.$rental_agreement->aadhar_proof;
                }
                if($rental_agreement->pan_proof!=''){
                    $rental_agreement->pan_proof   = url('/').'/public/uploads/'.$rental_agreement->pan_proof;
                }

                $search = ['status' => 1];
                $fields = ['id','property_name'];
                $Property_name = Property_name::getAll($fields,$search);

                $search = ['property_name.status' => 1,'property_name.id'=>$rental_agreement->property_id];
                $prdata = Property_name::getname($search);
                $rental_agreement->category_name = '';
                $rental_agreement->ownership_name = '';
                // echo $rental_agreement->property_id;exit;
                if(count($prdata)>0){
                    $rental_agreement->category_name = $prdata[0]['category_name'];
                    $rental_agreement->ownership_name = $prdata[0]['ownership_name'];
                }
                // echo "<pre>";print_r($rental_agreement);exit;
                $data = [
                    'rental_agreement' => $rental_agreement,
                    'Property_name' => $Property_name,
                ];

                return view('master.rental_agreement.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid rental_agreement"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['rental_agreement_management_access']['change_status']) || $rolesAccess['rental_agreement_management_access']['change_status']!=1){
            abort(403);
        } else {
            $rental_agreement  = Rental_agreement::where(['uuid' => $id])->first();
            $rental_agreement->status = ($rental_agreement->status) ? 0 : 1;
            $rental_agreement->save();

            $data = [
                'redirect_url' => url(route('rental-agreement-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "Status has been changed Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }

    public function delete($id)
    {
        $rolesAccess = Session::get('role_access');
         if(!isset($rolesAccess['rental_agreement_management_access']['delete']) || $rolesAccess['rental_agreement_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Rental_agreement::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('rental-agreement-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "rental_agreement has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
