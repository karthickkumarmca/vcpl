<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Staffdetails;
use App\Models\Siteinfo;
use App\Models\Sub_categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Session;

class SiteinfoController extends Controller
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
        if(!isset($rolesAccess['site_info_management']) || $rolesAccess['site_info_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'site_name'      => 'site_info.site_name',
                    'status'    => 'site_info.status',
                ];
                $sortField   = [
                    'site_name'     => 'site_info.site_name',
                    'status'  => 'site_info.status',
                    'date_created' => 'site_info.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'site_info.created_at',
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

                $records = Siteinfo::getlist($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Site info are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No site info details found";
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
                if(isset($rolesAccess['site_info_management_access'])){

                    $create_access          = $rolesAccess['site_info_management_access']['create'];
                    $view_access            = $rolesAccess['site_info_management_access']['view'];
                    $edit_access            = $rolesAccess['site_info_management_access']['edit'];
                    $change_status_access   = $rolesAccess['site_info_management_access']['change_status'];
                }

                return view('master.site_info.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['site_info_management_access']['create']) || $rolesAccess['site_info_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1,'user_groups_ids'=>2];
            $fields = ['id','name'];
            $Siteengineer = Staffdetails::getAll($fields,$search);

            $search = ['status' => 1,'sub_contractor'=>1];
            $fields = ['id','name'];
            $Subcontractor = Staffdetails::getAll($fields,$search);

            $search = ['status' => 1,'user_groups_ids'=>4];
            $fields = ['id','name'];
            $Storekeeper = Staffdetails::getAll($fields,$search);

            return view('master.site_info.create',compact('Siteengineer','Subcontractor','Storekeeper'));
        }
    }
    public function getSubCategory(Request $request) {

        $values ='<option value="">----  Select ---</option>';

        $search = ['status' => 1,'category_id'=>$request->get('id')];
        $fields = ['id','sub_category_name'];
        $data = Sub_categories::getAll($fields,$search);
        foreach($data as $c){
            $values .='<option value="'.$c['id'].'">'.$c['sub_category_name'].'</option>';
        }
        return $values;
        
    }
    public function store(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['site_info_management_access']['create']) || $rolesAccess['site_info_management_access']['create']!=1){
            abort(403);
        } else {

            if($request->has('site_name')){
                
                $site_info_id = $request->get('site_info_id');
                $fieldValidation = [
                'site_name'         => [
                    'required','min:2','max:50','unique:site_info,site_name,'.$site_info_id.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'site_name'         => [
                    'required','min:2','max:50','unique:site_info,site_name'
                ]
            ];
            }
            $fieldValidation['site_location']      = ['required','min:1','max:100'];
            $fieldValidation['site_engineer_id']   = ['sometimes','nullable',];
            $fieldValidation['sub_contractor_id']  = ['sometimes','nullable',];
            $fieldValidation['store_keeper_id']    = ['sometimes','nullable',];

            $errorMessages    = [
                'site_name.required'             => "Please enter the name",
                'site_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('site_info_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Siteinfo::storeRecords($request);
            }
            else{
                $response   = Siteinfo::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/site-info/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['site_info_management_access']['view']) || $rolesAccess['site_info_management_access']['view']!=1){
            abort(403);
        } else {
            $Siteinfo  = Siteinfo::getSiteInfoDetails(['uuid' => $id]);

            if ($Siteinfo) {

                $data = [
                    'data'    => $Siteinfo,
                ];

                return view('master.site_info.view', $data);
            } else {
                $data = [
                    'message' => "Invalid product_details"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['site_info_management_access']['edit']) || $rolesAccess['site_info_management_access']['edit']!=1){
            abort(403);
        } else {

        	$site_info_details  = Siteinfo::where(['uuid' => $id])->first();
        	if($site_info_details) {
	        	$search = ['status' => 1,'user_groups_ids'=>2];
	            $fields = ['id','name'];
	            $Siteengineer = Staffdetails::getAll($fields,$search);

	            $search1 = ['status' => 1,'sub_contractor'=>1];
	            $fields1 = ['id','name'];
	            $Subcontractor = Staffdetails::getAll($fields1,$search1);

	            $search = ['status' => 1,'user_groups_ids'=>4];
	            $fields = ['id','name'];
	            $Storekeeper = Staffdetails::getAll($fields,$search);
	            $data = [
	                    'site_info_details'    => $site_info_details,
	                    'site_engineer'        => $Siteengineer,
	                    'sub_contractor'       => $Subcontractor,
	                    'store_keeper'         => $Storekeeper,
	                ];
	                
	            return view('master.site_info.edit',$data);
            } else {
                $data = [
                    'message' => "Invalid site info details"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['site_info_management_access']['change_status']) || $rolesAccess['site_info_management_access']['change_status']!=1){
            abort(403);
        } else {
            $Siteinfo  = Siteinfo::where(['uuid' => $id])->first();
            $Siteinfo->status = ($Siteinfo->status) ? 0 : 1;
            $Siteinfo->save();

            $data = [
                'redirect_url' => url(route('site-info-list'))
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
         if(!isset($rolesAccess['site_info_management_access']['delete']) || $rolesAccess['site_info_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Siteinfo::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('site-info-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "Site info has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
