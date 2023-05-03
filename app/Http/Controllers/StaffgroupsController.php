<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Staffgroups;
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

class StaffgroupsController extends Controller
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
        if(!isset($rolesAccess['staffgroups_management']) || $rolesAccess['staffgroups_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'group_name'      => 'staffgroups.group_name',
                    'status'    => 'staffgroups.status',
                ];
                $sortField   = [
                    'group_name'     => 'staffgroups.group_name',
                    'status'  => 'staffgroups.status',
                    'date_created' => 'staffgroups.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'staffgroups.created_at',
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

                $records = Staffgroups::getstaffgroups($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['staffgroups'])) {
                    $statusCode = '200';
                    $message    = "staff groups are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No staff groups found";
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
                if(isset($rolesAccess['staffgroups_management_access'])){

                    $create_access          = $rolesAccess['staffgroups_management_access']['create'];
                    $view_access            = $rolesAccess['staffgroups_management_access']['view'];
                    $edit_access            = $rolesAccess['staffgroups_management_access']['edit'];
                    $change_status_access   = $rolesAccess['staffgroups_management_access']['change_status'];
                }

                return view('staffgroups.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['staffgroups_management_access']['create']) || $rolesAccess['staffgroups_management_access']['create']!=1){
            abort(403);
        } else {
            return view('staffgroups.create');
        }
    }
    public function store(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['staffgroups_management_access']['create']) || $rolesAccess['staffgroups_management_access']['create']!=1){
            abort(403);
        } else {

            $validation = config('field_validation.admin');
            $fieldValidation = [
                'group_name'         => [
                    'required','min:2','max:150'
                ]
            ];

            $errorMessages    = [
                'group_name.required'             => "Please enter the role",
                'group_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('staff_groups_id'))
            {
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Staffgroups::storeRecords($request);
            }
            else
            {
                $response   = Staffgroups::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('staffgroups-list/'); 

        }
    }

    public function edit($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['staffgroups_management_access']['edit']) || $rolesAccess['staffgroups_management_access']['edit']!=1){
            abort(403);
        } else {
            // $units  = units::find($id);
            $staffgroups  = Staffgroups::where(['uuid' => $id])->first();
            if ($staffgroups) {
                $data = [
                    'staffgroups' => $staffgroups,
                ];

                return view('staffgroups.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid staffgroups"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function view($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['staffgroups_management_access']['view']) || $rolesAccess['staffgroups_management_access']['view']!=1){
            abort(403);
        } else {
            $staffgroups  = Staffgroups::where(['uuid' => $id])->first();
            if ($staffgroups) {
                $data = [
                    'staffgroups' => $staffgroups,
                ];

                return view('staffgroups.view', $data);
            } else {
                $data = [
                    'message' => "Invalid staffgroups"
                ];

                return view('error_view', $data);
            }
        }
    }
    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['staffgroups_management_access']['change_status']) || $rolesAccess['staffgroups_management_access']['change_status']!=1){
            abort(403);
        } else {
            $staffgroups  = Staffgroups::where(['uuid' => $id])->first();
            $staffgroups->status = ($staffgroups->status) ? 0 : 1;
            $staffgroups->save();

            $data = [
                'redirect_url' => url(route('staffgroups-list'))
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
        if(!isset($rolesAccess['staffgroups_management_access']['delete']) || $rolesAccess['staffgroups_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Staffgroups::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('staffgroups-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "staffgroups has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
