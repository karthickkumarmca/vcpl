<?php

namespace App\Http\Controllers\master;

use Session;
use App\Admin;
use App\Models\Productdetails;
use App\Models\Architect_site;
use App\Models\Siteinfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

class Architect_siteController extends Controller
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
        if(!isset($rolesAccess['architect_site_management']) || $rolesAccess['architect_site_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'architect_name'      => 'architect_info.architect_name',
                    'status'    => 'architect_info.status',
                ];
                $sortField   = [
                    'status'  => 'architect_info.status',
                    'date_created' => 'architect_info.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'architect_info.created_at',
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

                $records = Architect_site::get_materials($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Data are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No architect info found";
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
                if(isset($rolesAccess['architect_site_management_access'])){

                    $create_access          = $rolesAccess['architect_site_management_access']['create'];
                    $view_access            = $rolesAccess['architect_site_management_access']['view'];
                    $edit_access            = $rolesAccess['architect_site_management_access']['edit'];
                    $change_status_access   = $rolesAccess['architect_site_management_access']['change_status'];
                    $delete_access   = $rolesAccess['architect_site_management_access']['delete'];
                }

                return view('master.architect_site.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['architect_site_management_access']['create']) || $rolesAccess['architect_site_management_access']['create']!=1){
            abort(403);
        } else {

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $siteinfo = Siteinfo::getAll($fields1,$search1);
            return view('master.architect_site.create',compact('siteinfo'));
        }
    }
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['architect_site_management_access']['create']) || $rolesAccess['architect_site_management_access']['create']!=1){
            abort(403);
        } else {

            if($request->has('architect_name_id')){
                
                $architect_name_id = $request->get('architect_name_id');
                $fieldValidation = [
                'architect_name'         => [
                    'required','min:2','max:15','unique:architect_info,architect_name,'.$architect_name_id.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'architect_name'         => [
                    'required','min:2','max:15','unique:architect_info,architect_name'
                ]
            ];
            }
           

            $errorMessages    = [
                'lorry_materials.required'             => "Please enter the name",
                'lorry_materials.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('architect_name_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Architect_site::storeRecords($request);
            }
            else{
                $response   = Architect_site::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/architect-site/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['architect_site_management_access']['view']) || $rolesAccess['architect_site_management_access']['view']!=1){
            abort(403);
        } else {

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $siteinfo = Siteinfo::getAll($fields1,$search1);

            $architect_site  = Architect_site::where(['uuid' => $id])->first();
            if ($architect_site) {

               
                $data = [
                    'siteinfo'         => $siteinfo,
                    'architect_site' => $architect_site,
                ];

                return view('master.architect_site.view', $data);
            } else {
                $data = [
                    'message' => "Invalid architect_site"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['architect_site_management_access']['edit']) || $rolesAccess['architect_site_management_access']['edit']!=1){
            abort(403);
        } else {
            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $siteinfo = Siteinfo::getAll($fields1,$search1);

            $architect_site  = Architect_site::where(['uuid' => $id])->first();
            if ($architect_site) {

               
                $data = [
                    'siteinfo'         => $siteinfo,
                    'architect_site' => $architect_site,
                ];

                return view('master.architect_site.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid architect_site"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['architect_site_management_access']['change_status']) || $rolesAccess['architect_site_management_access']['change_status']!=1){
            abort(403);
        } else {
            $architect_site  = Architect_site::where(['uuid' => $id])->first();
            $architect_site->status = ($architect_site->status) ? 0 : 1;
            $architect_site->save();

            $data = [
                'redirect_url' => url(route('architect-site-list'))
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
         if(!isset($rolesAccess['architect_site_management_access']['delete']) || $rolesAccess['architect_site_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Architect_site::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('architect-site-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "Data has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
