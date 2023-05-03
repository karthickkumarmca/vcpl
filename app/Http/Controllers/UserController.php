<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\User;
use App\Models\Staffdetails;
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
use Session;
use DB;

class UserController extends Controller
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
        if (!config("roles.{$role}.user_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'name'      => 'users.name',
                    'email'     => 'users.email',
                    'user_type' => 'users.user_type',
                    'status'    => 'users.status',
                ];
                $sortField   = [
                    'name'     => 'users.name',
                    'email'   => 'users.email',
                    'status'  => 'users.status',
                    'date_created' => 'users.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'users.created_at',
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
                    if (in_array($search_field, ["status", "user_type"])) {
                        array_push($search_filter, [$table_field, '=', $search_value]);
                    } else {
                        array_push($search_filter, [$table_field, 'LIKE', '%' . addslashes($search_value) . '%']);
                    }
                }

                $records = User::getUsers($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['users'])) {
                    $statusCode = '200';
                    $message    = "Users are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Users found";
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

                foreach (config('general_settings.user_type') as $key => $role) {
                    if ($key != config("user_role.testing_centre_admin")) {
                        $userTypes[] = ['label' => $role, 'value' => $key];
                    }
                }
                $role = session('user_role');
                $create_access     = config("roles.{$role}.user_management_access.create");
                $view_access     = config("roles.{$role}.user_management_access.view");
                $edit_access     = config("roles.{$role}.user_management_access.edit");
                $delete_access   = config("roles.{$role}.user_management_access.delete");
                $change_password_access   = config("roles.{$role}.user_management_access.change_password");
                $change_status_access   = config("roles.{$role}.user_management_access.change_status");

                return view('users.users-list', compact('statuses', 'userTypes', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_password_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management_access.create")) {
            abort(403);
        } else {
            // $flightnames     = Airlines::getAllDetails(['id AS value', 'name AS label']);
            foreach (config('general_settings.user_type') as $key => $role) {
                $userTypes[] = array(
                    'label' => $role,
                    'value' => $key
                );
            }
            return view('users.create', compact('userTypes'));
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management")) {
            abort(403);
        } else {

            $validation = config('field_validation.admin');

            app('validator')->extend('iunique', function ($attribute, $value, $parameters, $validator) {
                $query  = \DB::table('users');
                $column = $query->getGrammar()->wrap($parameters[1]);
                if (isset($parameters[3])) {
                    $query->where('uuid', '!=', $parameters[3]);
                }
                return !$query->whereRaw("lower({$column}) = lower(?)", [$value])
                ->count();
            });
            app('validator')->extend('check_email', function($attribute, $value, $parameters) {
                
                $query=User::select('id','name','user_type','uuid')->where('email',$value);
                if (isset($parameters[3])) {
                    $query->where('uuid', '!=', $parameters[3]);
                }
                $user_det_array=$query->get()->toArray();

                
                if(!empty($user_det_array)){
                    if($user_det_array[0]['user_type'] != 2)
                    {
                        return false;
                    }
                    if($user_det_array[0]['user_type'] == 2)
                    {
                        return true;
                    }
                }
                else
                {
                    return true;
                }
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
           
            if ($request->has('user_id')) {
                $fieldValidation['email'][] = 'check_email:users,email,' . $request->email . ',' . $request->user_id;
                $fieldValidation['phone'][] = 'iunique:users,phonenumber,' . $request->phone . ',' . $request->user_id;
            } else {
                $fieldValidation['email'][]='check_email:users,email,'. $request->email;
                $fieldValidation['phone'][] = 'iunique:users,phonenumber,' . $request->phone;
                $fieldValidation['password'] = [
                    'required',
                    'max:' . $validation['password']['max'],
                    'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',

                ];
                $fieldValidation['confirm_password'] = [
                    'required',
                    'max:' . $validation['password']['max'],
                    'same:password'
                ];


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
                'password.required'         => "Please enter the password",
                'password.regex'            => "Password must be at least one uppercase, one lowercase letter, one numeric value, one special character and must be more than 8 characters long.",
                'password.max'              => "Password should have maximum of " . $validation['password']['max'] . " characters",
                'confirm_password.required' => "Please enter the confirm password",
                'confirm_password.max'      => "Confirm Password should have maximum of " . $validation['password']['max'] . " characters",
                'confirm_password.same'     => "Password does not match",
                'email.check_email'    => "Email address is already exist",
                'name.regex'=>'Name should accept only letters',
                'phone.regex'=>'Phone Number should accept only numeric values'
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            // $request->validate([
            //     'user_image' => 'image|mimes:jpeg,png,jpg|max:5120',
            // ]);

            $imageurl = '';
            // if ($request->file('user_image')) {
            //     $image = $request->file('user_image');
            //     $extension = strtolower($image->getClientOriginalExtension());

            //     if (!in_array($extension, ['jpeg', 'png', 'jpg'])) {
            //         return Redirect::back()->with('user_image_error', 'The upload image must be an image.');
            //         exit;
            //     }
                
            //     $image_size = $image->getSize() / 1024;
            //     if ($image_size > 5120 || $image_size == 0) {
            //         return Redirect::back()->with('user_image_error', 'File size exceeded max limit of 5MB.. Please upload file of a smaller size.');
            //         exit;
            //     }

            //     $imageFileName = md5(strtolower(str_replace(" ", "_",  env('APP_NAME'))) . date('YmdHis') . '_imageuploaded');

            //     $filePath      = env('AWS_BUCKET') . '/attachments/' . $imageFileName;
            //     $s3            = Storage::disk('s3');
            //     $s3->put($filePath, file_get_contents($image));
            //     $imageurl = $filePath;
            // }

            $check=$this->checkemail($request->email);
            if($check['exists'] == 1)
            {
                $request['user_id']=isset($check['userid'])?$check['userid']:0;
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = User::storeRecords($request,$imageurl);
            }
            else
            {
                $response   = User::storeRecords($request,$imageurl); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('user-list/'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management_access.view")) {
            abort(403);
        } else {
            $user  = User::where(['uuid' => $id])->first();
            $airlinesIDs = isset($user["airlines_id"]) ? $user["airlines_id"] : "";
            $flightnames = [];
            if ($airlinesIDs) {
                $flightnames     = Airlines::getFlightDetails($airlinesIDs);
            }

            if ($user) {
                $data = [
                    'user' => $user,
                    'flight_names' => $flightnames
                ];

                return view('users.view', $data);
            } else {
                $data = [
                    'message' => "Invalid User"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management_access.edit")) {
            abort(403);
        } else {
            // $user  = User::find($id);
            $user  = User::where(['uuid' => $id])->first();
            if ($user) {
                //$flightnames     = Airlines::getAllDetails(['id AS value', 'name AS label']);
                foreach (config('general_settings.user_type') as $key => $role) {
                    $userTypes[] = array(
                        'label' => $role,
                        'value' => $key
                    );
                }
               // $centers = TestingCentre::getAll(['id', 'name']);
                $data = [
                    'user' => $user,
                    'userTypes' => $userTypes,
                    //'flightnames' => $flightnames,
                    //'centers' => $centers,
                ];

                return view('users.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid User"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management_access.edit")) {
            abort(403);
        } else {
            $user  = User::where(['uuid' => $id])->first();
            $user->status = ($user->status) ? 0 : 1;
            $user->save();

            $data = [
                'redirect_url' => url(route('user-list'))
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


    public function changePassword(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.change_profile_password")) {
            abort(403);
        } else {
            if ($request->isMethod('post')) {

                //$admin          = User::find(Auth::id());
                $LoggedInUserType = !empty(session('user_type')) ? session('user_type') : "";
                $role             = session('user_role');
                $admin            = User::where(['id' => Auth::id(), 'user_type' => $LoggedInUserType])->first();

                if (!config("roles.{$role}.change_profile_password")) {
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
                if ($admin) {
                    $fieldValidation = [
                        'new_password'   => ['required', 'max:100', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
                        'confirm_password'  => ['required', 'max:100'],
                        'current_password'  => ['required', 'max:100']
                    ];
                   
                    $errorMessages    = [
                        'current_password.required'      => "Please enter the current password",
                        'new_password.required'      => "Please enter the password",
                        'confirm_password.required'  => "Please enter the confirm password",
                        'new_password.max'          => "Password should have maximum of 100 characters",
                        'new_password.regex'          => "Password must be at least one uppercase, one lowercase letter, one numeric value, one special character and must be more than 8 characters long.",

                    ];
                    $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);
                    if ($validator->fails()) {
                        return $this->createErrorResponse($validator->errors());
                    }

                    if (Hash::check($request->get('current_password'), $admin->password)) {
                        if ($request->get('current_password') == $request->get('new_password')) {
                            $response = response()->json([
                                'message' => "Your current password & new password cant be same. Try new one.",
                                'data'    => (object)[],
                                'error'   => (object)[]
                            ], 400);
                        } else {
                            $admin->password = Hash::make($request->get('new_password'));
                            $admin->save();
                            //DB::table('sessions')->where('user_id', $admin->id)->delete();
                            $response = response()->json([
                                'message' => "Password changed successfully",
                                'data'    => (object)[],
                                'error'   => (object)[]
                            ], 200);
                        }
                    } else {
                        $response = response()->json([
                            'message' => "Your current password is invalid. Try again.",
                            'data'    => (object)[],
                            'error'   => (object)[]
                        ], 400);
                    }

                    return $response;
                } else {
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
            } else {
                return view('users.change_user_password');
            }
        }
    }

    public function changeUserPassword(Request $request, string $id, $usertype = 0)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management_access.change_password")) {
            abort(403);
        } else {
            if ($request->isMethod('post')) {
                $userID           = !empty($request->get('id')) ? str_replace(env('APP_KEY'), "", decrypt($request->get('id'))) : "";
                $userRole         = !empty($request->get('userrole')) ? $request->get('userrole') : "";
                $LoggedInUserType = !empty(session('user_type')) ? session('user_type') : "";
                $role             = session('user_role');

                if (($LoggedInUserType != 1) || !config("roles.{$role}.user_management")) {
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
                $fieldValidation = [
                    'new_password'   => ['required', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
                    'confirm_password'  => ['required'],
                    'id'  => ['required']
                ];
                $errorMessages    = [
                    'new_password.required'      => "Please enter the password",
                    'confirm_password.required'  => "Please enter the confirm password",
                    'new_password.regex'          => "Password must be at least one uppercase, one lowercase letter, one numeric value, one special character and must be more than 8 characters long.",

                ];
                $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);
                if ($validator->fails()) {
                    return $this->createErrorResponse($validator->errors());
                }
                //$admin = User::find($userID);
                $admin  = User::where(['id' => $userID, 'user_type' => $userRole])->first();
                if ($admin) {
                    $admin->password = Hash::make($request->get('new_password'));
                    $admin->save();
                    
                    $response = response()->json([
                        'message' => "Password changed successfully",
                        'data'    => (object)[],
                        'error'   => (object)[]
                    ]);
                    return $response;
                } else {
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
            } else {
                $role = session('user_role');
                if (!config("roles.{$role}.user_management")) {
                    abort(403);
                } else {

                    $userID = str_replace(env('APP_KEY'), "", decrypt($id));
                    $user = User::find($userID);
                    return view('admin.change_password', [
                        'id' => $id,
                        'userrole' => $usertype,
                        'user'=>$user
                    ]);
                }
            }
        }
    }

    public function delete($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management_access.delete")) {
            abort(403);
        } else {
            $result = User::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('user-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "User has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
    public function changeStaffPassword(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.reset_staff_password")) {
            abort(403);
        } else {
            if ($request->isMethod('post')) {
                //$admin          = User::find(Auth::id());
                $LoggedInUserType = !empty(session('user_type')) ? session('user_type') : "";
                $role             = session('user_role');
                $userid           = !empty($request->user_id)?Helper::getlogindata($request->user_id,1):'';
                if(empty($userid)){
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
                $admin            = User::where(['uuid' =>$userid])->first(); //, 'user_type' => $LoggedInUserType
                $Staffdetails     = Staffdetails::where(['uuid' =>$userid])->first();
                if (!config("roles.{$role}.reset_staff_password")) {
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
                if ($admin) {
                    $fieldValidation = [
                        'new_password'   => ['required', 'max:100', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
                        'confirm_password'  => ['required', 'max:100'],
                    ];
                   
                    $errorMessages    = [
                        'new_password.required'      => "Please enter the password",
                        'confirm_password.required'  => "Please enter the confirm password",
                        'new_password.max'          => "Password should have maximum of 100 characters",
                        'new_password.regex'          => "Password must be at least one uppercase, one lowercase letter, one numeric value, one special character and must be more than 8 characters long.",

                    ];
                    $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);
                    if ($validator->fails()) {
                        return $this->createErrorResponse($validator->errors());
                    }
                    $admin->password = Hash::make($request->get('new_password'));
                    $admin->save();

                    $Staffdetails->password = $request->get('new_password');
                    $Staffdetails->save();
                    
                    //DB::table('sessions')->where('user_id', $admin->id)->delete();
                    $response = response()->json([
                        'message' => "Password changed successfully",
                        'data'    => (object)[],
                        'error'   => (object)[]
                    ], 200);

                    /*if (Hash::check($request->get('current_password'), $admin->password)) {
                        if ($request->get('current_password') == $request->get('new_password')) {
                            $response = response()->json([
                                'message' => "Your current password & new password cant be same. Try new one.",
                                'data'    => (object)[],
                                'error'   => (object)[]
                            ], 400);
                        } else {
                            $admin->password = Hash::make($request->get('new_password'));
                            $admin->save();
                            //DB::table('sessions')->where('user_id', $admin->id)->delete();
                            $response = response()->json([
                                'message' => "Password changed successfully",
                                'data'    => (object)[],
                                'error'   => (object)[]
                            ], 200);
                        }
                    } else {
                        $response = response()->json([
                            'message' => "Your current password is invalid. Try again.",
                            'data'    => (object)[],
                            'error'   => (object)[]
                        ], 400);
                    }*/

                    return $response;
                } else {
                    return response()->json([
                        'message' => "Invalid details,Please try again.",
                        'data' => (object)[],
                        'error' => (object)[]
                    ], 400);
                }
            } else {
                if(!empty($request->id)){
                    $staff_details  = Staffdetails::where(['uuid' => $request->id])->first();
                    if($staff_details){
                        $details = ['id' => $request->id,'encrypt_id' => Helper::getlogindata($staff_details->uuid,0)];
                        $data = [
                            'data'    => $details,
                        ];
                        return view('master.staff_details.change_user_password',$data);
                    } else {
                        $data = [ 'message' => "Invalid staff details"];
                        return view('error_view', $data);
                    }
                } else {
                    $data = [ 'message' => "Invalid staff details"];
                    return view('error_view', $data);
                }
            }
        }
    }
    public function checkemail($email)
    {
        $user_array=User::select('id','name','user_type','uuid')->where('email',$email)->get()->toArray();
        if(!empty($user_array))
        {
            $userid=isset($user_array[0]['uuid'])?$user_array[0]['uuid']:0;
            if($user_array[0]['user_type'] == 3)
            {
                $user_detail=array('status' =>true,'exists' =>1,"userid"=>$userid);
                return $user_detail;
            }
            else
            {
               $user_detail=array('status' =>true,'exists' =>0,"userid"=>$userid);
               return $user_detail; 
           }
       }
       else
       {
        $user_detail=array('status' =>true,'exists' =>02,"userid"=>0);
        return $user_detail; 
    }
}
}
