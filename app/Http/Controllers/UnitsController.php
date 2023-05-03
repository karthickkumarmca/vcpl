<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Units;
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

class UnitsController extends Controller
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
        // echo "<pre>";print_r($rolesAccess);exit;
        if(!isset($rolesAccess['units_management']) || $rolesAccess['units_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'unit_name'      => 'units.unit_name',
                    'status'    => 'units.status',
                ];
                $sortField   = [
                    'unit_name'     => 'units.unit_name',
                    'status'  => 'units.status',
                    'date_created' => 'units.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'units.created_at',
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

                $records = Units::getunits($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['units'])) {
                    $statusCode = '200';
                    $message    = "units are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No units found";
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
                if(isset($rolesAccess['units_management_access'])){

                    $create_access          = $rolesAccess['units_management_access']['create'];
                    $view_access            = $rolesAccess['units_management_access']['view'];
                    $edit_access            = $rolesAccess['units_management_access']['edit'];
                    $change_status_access   = $rolesAccess['units_management_access']['change_status'];
                }
                

                return view('units.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['units_management_access']['create']) || $rolesAccess['units_management_access']['create']!=1){
            abort(403);
        } else {
            return view('units.create');
        }
    }
    public function store(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['units_management_access']['create']) || $rolesAccess['units_management_access']['create']!=1){
            abort(403);
        } else {

            $validation = config('field_validation.admin');
            $fieldValidation = [
                'unit_name'         => [
                    'required','min:1','max:15'
                ]
            ];

            $errorMessages    = [
                'unit_name.required'             => "Please enter the name",
                'unit_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('units_id'))
            {
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Units::storeRecords($request);
            }
            else
            {
                $response   = Units::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('units-list/'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['units_management_access']['view']) || $rolesAccess['units_management_access']['view']!=1){
            abort(403);
        } else {
            $units  = Units::where(['uuid' => $id])->first();
            if ($units) {
                $data = [
                    'units' => $units,
                ];

                return view('units.view', $data);
            } else {
                $data = [
                    'message' => "Invalid units"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['units_management_access']['edit']) || $rolesAccess['units_management_access']['edit']!=1){
            abort(403);
        } else {
            // $units  = units::find($id);
            $units  = Units::where(['uuid' => $id])->first();
            if ($units) {
                $data = [
                    'units' => $units,
                ];

                return view('units.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid units"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['units_management_access']['change_status']) || $rolesAccess['units_management_access']['change_status']!=1){
            abort(403);
        } else {
            $units  = Units::where(['uuid' => $id])->first();
            $units->status = ($units->status) ? 0 : 1;
            $units->save();

            $data = [
                'redirect_url' => url(route('units-list'))
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
        if(!isset($rolesAccess['units_management_access']['delete']) || $rolesAccess['units_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Units::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('units-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "units has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
