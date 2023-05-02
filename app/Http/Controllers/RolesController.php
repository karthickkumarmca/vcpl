<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Roles;
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
use Session;

class RolesController extends Controller
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
        if(!isset($rolesAccess['roles_management']) || $rolesAccess['roles_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'role_name'      => 'roles.role_name',
                    'status'    => 'roles.status',
                ];
                $sortField   = [
                    'role_name'     => 'roles.role_name',
                    'status'  => 'roles.status',
                    'date_created' => 'roles.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'roles.created_at',
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

                $records = Roles::getroles($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['roles'])) {
                    $statusCode = '200';
                    $message    = "Roles are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No roles found";
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
                if(isset($rolesAccess['roles_management_access'])){

                    $create_access          = $rolesAccess['roles_management_access']['create'];
                    $view_access            = $rolesAccess['roles_management_access']['view'];
                    $edit_access            = $rolesAccess['roles_management_access']['edit'];
                    $change_status_access   = $rolesAccess['roles_management_access']['change_status'];
                }

                return view('roles.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['roles_management_access']['create']) || $rolesAccess['roles_management_access']['create']!=1){
            abort(403);
        } else {
            return view('roles.create');
        }
    }
    public function store(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['roles_management_access']['create']) || $rolesAccess['roles_management_access']['create']!=1){
            abort(403);
        } else {

            $validation = config('field_validation.admin');
            $fieldValidation = [
                'role_name'         => [
                    'required','min:2','max:15'
                ],
                'master'         => [ 'required']
            ];


            $errorMessages    = [
                'role_name.required'             => "Please enter the role",
                'role_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('roles_id')){

                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Roles::storeRecords($request);
            }
            else
            {
                $response   = Roles::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('roles-list/'); 

        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['roles_management_access']['edit']) || $rolesAccess['roles_management_access']['edit']!=1){
            abort(403);
        } else {
            // $units  = units::find($id);
            $roles  = Roles::where(['uuid' => $id])->first();
            if($roles->master!=''){
                $roles->master = explode(",",$roles->master);
            }
            if ($roles) {
                $data = [
                    'roles' => $roles,
                ];

                return view('roles.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid roles"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['roles_management_access']['view']) || $rolesAccess['roles_management_access']['view']!=1){
            abort(403);
        } else {
            $roles  = Roles::where(['uuid' => $id])->first();
            if ($roles) {
                if($roles->master!=''){
                    $roles->master = explode(",",$roles->master);
                }
                $data = [
                    'roles' => $roles,
                ];

                return view('roles.view', $data);
            } else {
                $data = [
                    'message' => "Invalid roles"
                ];

                return view('error_view', $data);
            }
        }
    }
    public function updateStatus($id)
    {
         if(!isset($rolesAccess['roles_management_access']['change_status']) || $rolesAccess['roles_management_access']['change_status']!=1){
            abort(403);
        } else {
            $roles  = Roles::where(['uuid' => $id])->first();
            $roles->status = ($roles->status) ? 0 : 1;
            $roles->save();

            $data = [
                'redirect_url' => url(route('roles-list'))
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
         if(!isset($rolesAccess['roles_management_access']['delete']) || $rolesAccess['roles_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Roles::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('roles-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "roles has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
