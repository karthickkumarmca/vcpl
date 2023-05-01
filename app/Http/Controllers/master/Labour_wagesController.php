<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Staffdetails;
use App\Models\Labour_categories;
use App\Models\Labour_wages;
use App\Models\Siteinfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Session;

class Labour_wagesController extends Controller
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
        if(!isset($rolesAccess['labour_wages_management']) || $rolesAccess['labour_wages_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'client_name'      => 'labour_wages.client_name',
                    'status'    => 'labour_wages.status',
                ];
                $sortField   = [
                    'status'  => 'labour_wages.status',
                    'date_created' => 'labour_wages.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'labour_wages.created_at',
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

                $records = Labour_wages::get_materials($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Data are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No client info found";
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
                if(isset($rolesAccess['labour_wages_management_access'])){

                    $create_access          = $rolesAccess['labour_wages_management_access']['create'];
                    $view_access            = $rolesAccess['labour_wages_management_access']['view'];
                    $edit_access            = $rolesAccess['labour_wages_management_access']['edit'];
                    $change_status_access   = $rolesAccess['labour_wages_management_access']['change_status'];
                    $delete_access   = $rolesAccess['labour_wages_management_access']['delete'];
                }

                return view('master.labour_wages.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_wages_management_access']['create']) || $rolesAccess['labour_wages_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Labour_categories::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $siteinfo = Siteinfo::getAll($fields1,$search1);

            $search2 = ['status' => 1,'sub_contractor'=>1];
            $fields2 = ['id','name'];
            $staffdetails = Staffdetails::getAll($fields2,$search2);
            return view('master.labour_wages.create',compact('categories','siteinfo','staffdetails'));
        }
    }
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_wages_management_access']['create']) || $rolesAccess['labour_wages_management_access']['create']!=1){
            abort(403);
        } else {

            $fieldValidation['rate']                    = ['required'];
            $fieldValidation['labour_category_id']      = ['required'];
            $fieldValidation['site_id']                 = ['required' ];
            $fieldValidation['sub_contractor_id']       = ['required' ];

            $errorMessages    = [
                'client_name.required'             => "Please enter the name",
                'client_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('client_name_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Labour_wages::storeRecords($request);
            }
            else{
                $response   = Labour_wages::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect(url(route('labour-wages-list'))); 

        }
    }

    public function view($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_wages_management_access']['view']) || $rolesAccess['labour_wages_management_access']['view']!=1){
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Labour_categories::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $Siteinfo = Siteinfo::getAll($fields1,$search1);

            $labour_wages  = Labour_wages::where(['uuid' => $id])->first();
            if ($labour_wages) {

                $search2 = ['status' => 1,'sub_contractor'=>1];
                $fields2 = ['id','name'];
                $staffdetails = Staffdetails::getAll($fields2,$search2);

                $data = [
                    'staffdetails'     => $staffdetails,
                    'siteinfo'         => $Siteinfo,
                    'labour_wages' => $labour_wages,
                    'categories'     => $categories,
                ];

                return view('master.labour_wages.view', $data);
            } else {
                $data = [
                    'message' => "Invalid labour_wages"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_wages_management_access']['edit']) || $rolesAccess['labour_wages_management_access']['edit']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Labour_categories::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $Siteinfo = Siteinfo::getAll($fields1,$search1);

            $labour_wages  = Labour_wages::where(['uuid' => $id])->first();
            if ($labour_wages) {

                $search2 = ['status' => 1,'sub_contractor'=>1];
                $fields2 = ['id','name'];
                $staffdetails = Staffdetails::getAll($fields2,$search2);
               
                $data = [
                    'staffdetails'      => $staffdetails,
                    'siteinfo'          => $Siteinfo,
                    'labour_wages'      => $labour_wages,
                    'categories'        => $categories,
                ];

                return view('master.labour_wages.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid labour_wages"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        if(!isset($rolesAccess['labour_wages_management_access']['change_status']) || $rolesAccess['labour_wages_management_access']['change_status']!=1){
            abort(403);
        } else {
            $labour_wages  = Labour_wages::where(['uuid' => $id])->first();
            $labour_wages->status = ($labour_wages->status) ? 0 : 1;
            $labour_wages->save();

            $data = [
                'redirect_url' => url(route('labour-wages-list'))
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
         if(!isset($rolesAccess['labour_wages_management_access']['delete']) || $rolesAccess['labour_wages_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Labour_wages::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('labour-wages-list'))
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
