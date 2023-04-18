<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use App\Helpers\Helper;
use DB;

class CustomerController extends Controller
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
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'name'      => 'customers.name',
                    'email'     => 'customers.email',
                    'status'    => 'customers.status',
                ];
                $sortField   = [
                    'name'     => 'customers.name',
                    'email'   => 'customers.email',
                    'status'  => 'customers.status',
                    'date_created' => 'customers.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'customers.created_at',
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

                $records = Customer::getCustomers($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['customers'])) {
                    $statusCode = '200';
                    $message    = "Customers are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Customers found";
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

                $role = session('user_role');
                $create_access     = config("roles.{$role}.customer_management_access.create");
                $view_access     = config("roles.{$role}.customer_management_access.view");
                $edit_access     = config("roles.{$role}.customer_management_access.edit");
                $delete_access   = config("roles.{$role}.customer_management_access.delete");
                $change_status_access   = config("roles.{$role}.customer_management_access.change_status");

                return view('customers.customers-list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management_access.create")) {
            abort(403);
        } else {
            return view('customers.create');
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management")) {
            abort(403);
        } else {

            $validation = config('field_validation.admin');

            app('validator')->extend('iunique', function ($attribute, $value, $parameters, $validator) {
                $query  = \DB::table('customers');
                $column = $query->getGrammar()->wrap($parameters[1]);
                if (isset($parameters[3])) {
                    $query->where('uuid', '!=', $parameters[3]);
                }
                return !$query->whereRaw("lower({$column}) = lower(?)", [$value])
                ->count();
            });
            app('validator')->extend('check_email', function($attribute, $value, $parameters) {
                
                $query=Customer::select('id','name','uuid')->where('email',$value);
                if (isset($parameters[3])) {
                    $query->where('uuid', '!=', $parameters[3]);
                }
                $customer_det_array=$query->get()->toArray();
                return true;
            });
            $fieldValidation = [
                'name'   => [
                    'required',
                    'max:' . $validation['name']['max'],
                    'min:' . $validation['name']['min'],
                    'regex:/^[a-zA-Z ]+$/',
                ],
                'email'         => [
                    'required',
                    'email',
                    'max:' . $validation['email']['max'],
                ],
                'phone'         => [
                    'required',
                    'min:' . $validation['phone']['min'],
                    'max:' . $validation['phone']['max'],
                    'regex:/^([0-9\s\-\+\(\)]*)$/',
                ]
            ];
            if (App::environment() != "local") {
                $fieldValidation['g-recaptcha-response'] = ['required','captcha'];
            }
            if ($request->has('customer_id')) {
                $fieldValidation['email'][] = 'check_email:customers,email,' . $request->email . ',' . $request->customer_id;
                $fieldValidation['phone'][] = 'iunique:customers,phonenumber,' . $request->phone . ',' . $request->customer_id;
            } else {
                $fieldValidation['email'][]='check_email:customers,email,'. $request->email;
                $fieldValidation['phone'][] = 'iunique:customers,phonenumber,' . $request->phone;
            }

            $errorMessages    = [
                'name.required'             => "Please enter the name",
                'name.min'                  => "Name should have minimum of " . $validation['name']['min'] . " characters",
                'name.max'                  => "Name should have maximum of " . $validation['name']['max'] . " characters",
                'email.required'            => "Please enter the email",
                'email.email'               => "Please enter the valid email address",
                'email.max'                 => "Email should have maximum of " . $validation['email']['max'] . " characters",
                'email.iunique'             => "Email address is already exist",
                'phone.required'            => "Please enter the phone number",
                'phone.iunique'             => "Phone number is already exist",
                'phone.min'                 => "Phone number must be minimum" . $validation['phone']['min'] . " characters",
                'phone.max'                 => "Phone number must be maximum " . $validation['phone']['max'] . " characters",
                'email.check_email'    => "Email address is already exist",
                'name.regex'=>'Name should accept only letters',
                'phone.regex'=>'Phone Number should accept only numeric values'
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            $check=$this->checkemail($request->email);
            if($check['exists'] == 1)
            {
                $request['customer_id']=isset($check['customerid'])?$check['customerid']:0;
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Customer::storeRecords($request);
            }
            else
            {
                $response   = Customer::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('customer-list/'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management_access.view")) {
            abort(403);
        } else {
            $customer  = Customer::where(['uuid' => $id])->first();
            if ($customer) {
                $data = [
                    'customer' => $customer,
                ];

                return view('customers.view', $data);
            } else {
                $data = [
                    'message' => "Invalid customer"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management_access.edit")) {
            abort(403);
        } else {
            // $customer  = Customer::find($id);
            $customer  = Customer::where(['uuid' => $id])->first();
            if ($customer) {
                $data = [
                    'customer' => $customer,
                ];

                return view('customers.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid customer"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management_access.edit")) {
            abort(403);
        } else {
            $customer  = Customer::where(['uuid' => $id])->first();
            $customer->status = ($customer->status) ? 0 : 1;
            $customer->save();

            $data = [
                'redirect_url' => url(route('customer-list'))
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
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management_access.delete")) {
            abort(403);
        } else {
            $result = Customer::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('customer-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "customer has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }

    public function checkemail($email)
    {
        $customer_array=Customer::select('id','name','uuid')->where('email',$email)->get()->toArray();
        if(!empty($customer_array))
        {
            $customerid=isset($customer_array[0]['uuid'])?$customer_array[0]['uuid']:0;
            $customer_detail=array('status' =>true,'exists' =>1,"customerid"=>$customerid);
                return $customer_detail;
        }
        else
        {
            $customer_detail=array('status' =>true,'exists' =>0,"customerid"=>0);
            return $customer_detail; 
        }
    }
}
