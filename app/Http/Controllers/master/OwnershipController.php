<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Ownership;
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
use DB;
use Session;

class OwnershipController extends Controller
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
        if(!isset($rolesAccess['ownership_management']) || $rolesAccess['ownership_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'status'                => 'ownership.status',
                    'staff_name'            => 'ownership.id',
                    'position'              => 'ownership.position',
                ];
                $sortField   = [
                    'category_name'     => 'ownership.category_name',
                    'status'  => 'ownership.status',
                    'date_created' => 'ownership.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'ownership.created_at',
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

                $records = Ownership::getList($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Ownership are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Ownership found";
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

                $Staffdetails = Ownership::getOwnerNamelist();

                $create_access = $view_access = $edit_access = $delete_access = $change_status_access = 0;
                if(isset($rolesAccess['ownership_management_access'])){

                    $create_access          = $rolesAccess['ownership_management_access']['create'];
                    $view_access            = $rolesAccess['ownership_management_access']['view'];
                    $edit_access            = $rolesAccess['ownership_management_access']['edit'];
                    $change_status_access   = $rolesAccess['ownership_management_access']['change_status'];
                    $delete_access   = $rolesAccess['ownership_management_access']['delete'];
                }

                return view('master.ownership.list', compact('statuses','Staffdetails', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['ownership_management_access']['create']) || $rolesAccess['ownership_management_access']['create']!=1){
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','name'];
            $Staffdetails = Staffdetails::getAll($fields,$search);

            return view('master.ownership.create',compact('Staffdetails'));
        }
    }
    public function store(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['ownership_management_access']['create']) || $rolesAccess['ownership_management_access']['create']!=1){
            abort(403);
        } else {

           
            $fieldValidation['staff_id']    = ['required'];
            $fieldValidation['position']    = ['required','min:2','max:50'];
           

            $errorMessages    = [
                'ownership_name.required'             => "Please enter the name",
                'ownership_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('ownership_id')){

                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Ownership::storeRecords($request);
            }
            else{
                $response   = Ownership::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('master/ownership/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['ownership_management_access']['view']) || $rolesAccess['ownership_management_access']['view']!=1){
            abort(403);
        } else {
            $ownership  = Ownership::where(['uuid' => $id])->first();
            if ($ownership) {

                $search = ['status' => 1,'id'=>$ownership->staff_id];
                $fields = ['id','name'];
                $Staffdetails = Staffdetails::getAll($fields,$search);
                $ownership->staff_name = '';
                if(count($Staffdetails)>0){
                    // echo "<pre>";print_r($Staffdetails);exit;
                    $ownership->staff_name = $Staffdetails[0]['name'];
                }

                $data = [
                    'ownership' => $ownership,
                ];

                return view('master.ownership.view', $data);
            } else {
                $data = [
                    'message' => "Invalid ownership"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['ownership_management_access']['edit']) || $rolesAccess['ownership_management_access']['edit']!=1){
            abort(403);
        } else {
            // $categories  = Ownership::find($id);
            $ownership  = Ownership::where(['uuid' => $id])->first();
            if ($ownership) {

                $search = ['status' => 1];
                $fields = ['id','name'];
                $Staffdetails = Staffdetails::getAll($fields,$search);
                $data = [
                    'ownership' => $ownership,
                    'Staffdetails' => $Staffdetails,
                ];

                return view('master.ownership.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid ownership"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['ownership_management_access']['change_status']) || $rolesAccess['ownership_management_access']['change_status']!=1){
            abort(403);
        } else {
            $categories  = Ownership::where(['uuid' => $id])->first();
            $categories->status = ($categories->status) ? 0 : 1;
            $categories->save();

            $data = [
                'redirect_url' => url(route('ownership-list'))
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
         if(!isset($rolesAccess['ownership_management_access']['delete']) || $rolesAccess['ownership_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Ownership::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('ownership-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "categories has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
