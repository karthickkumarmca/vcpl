<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Productdetails;
use App\Models\Client_site;
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

class Client_siteController extends Controller
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
        if(!isset($rolesAccess['client_site_management']) || $rolesAccess['client_site_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'client_name'      => 'client_info.client_name',
                    'status'    => 'client_info.status',
                ];
                $sortField   = [
                    'status'  => 'client_info.status',
                    'date_created' => 'client_info.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'client_info.created_at',
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

                $records = Client_site::get_materials($page, $offset, $sort, $search_filter);
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
                if(isset($rolesAccess['client_site_management_access'])){

                    $create_access          = $rolesAccess['client_site_management_access']['create'];
                    $view_access            = $rolesAccess['client_site_management_access']['view'];
                    $edit_access            = $rolesAccess['client_site_management_access']['edit'];
                    $change_status_access   = $rolesAccess['client_site_management_access']['change_status'];
                    $delete_access          = $rolesAccess['client_site_management_access']['delete'];
                }

                return view('master.client_site.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['client_site_management_access']['create']) || $rolesAccess['client_site_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1,'category_id'=>6];
            $fields = ['id','product_name'];
            $categories = Productdetails::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $siteinfo = Siteinfo::getAll($fields1,$search1);
            return view('master.client_site.create',compact('categories','siteinfo'));
        }
    }
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['client_site_management_access']['create']) || $rolesAccess['client_site_management_access']['create']!=1){
            abort(403);
        } else {

            if($request->has('client_name_id')){
                
                $client_name_id = $request->get('client_name_id');
                $fieldValidation = [
                'client_name'         => [
                    'required','min:2','max:15','unique:client_info,client_name,'.$client_name_id.',uuid'
                ]
                ];
            }
            else{
                $fieldValidation = [
                'client_name'         => [
                    'required','min:2','max:15','unique:client_info,client_name'
                ]
            ];
            }
            
            $fieldValidation['site_id']             = ['required' ];
            $fieldValidation['cader']               = ['required','min:2','max:25' ];
            $fieldValidation['address']             = ['required','min:2','max:125' ];
            $fieldValidation['mobile_number']       = ['required','numeric' ];
            // $fieldValidation['email_id']            = ['required','min:8','max:56','email' ];

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
                $response   = Client_site::storeRecords($request);
            }
            else{
                $response   = Client_site::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect(url(route('client-info-list'))); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['client_site_management_access']['view']) || $rolesAccess['client_site_management_access']['view']!=1){
            abort(403);
        } else {

            $search = ['status' => 1,'category_id'=>6];
            $fields = ['id','product_name'];
            $categories = Productdetails::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $Siteinfo = Siteinfo::getAll($fields1,$search1);

            $client_site  = Client_site::where(['uuid' => $id])->first();
            if ($client_site) {

               
                $data = [
                    'siteinfo'         => $Siteinfo,
                    'client_site' => $client_site,
                    'categories'     => $categories,
                ];

                return view('master.client_site.view', $data);
            } else {
                $data = [
                    'message' => "Invalid client_site"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['client_site_management_access']['edit']) || $rolesAccess['client_site_management_access']['edit']!=1){
            abort(403);
        } else {
            $search = ['status' => 1,'category_id'=>6];
            $fields = ['id','product_name'];
            $categories = Productdetails::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','site_name'];
            $Siteinfo = Siteinfo::getAll($fields1,$search1);

            $client_site  = Client_site::where(['uuid' => $id])->first();
            if ($client_site) {

               
                $data = [
                    'siteinfo'         => $Siteinfo,
                    'client_site' => $client_site,
                    'categories'     => $categories,
                ];

                return view('master.client_site.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid client_site"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
         if(!isset($rolesAccess['client_site_management_access']['change_status']) || $rolesAccess['client_site_management_access']['change_status']!=1){
            abort(403);
        } else {
            $client_site  = Client_site::where(['uuid' => $id])->first();
            $client_site->status = ($client_site->status) ? 0 : 1;
            $client_site->save();

            $data = [
                'redirect_url' => url(route('client-info-list'))
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
        if(!isset($rolesAccess['client_site_management_access']['delete']) || $rolesAccess['client_site_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Client_site::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('client-info-list'))
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
