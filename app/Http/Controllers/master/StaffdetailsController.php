<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Staffgroups;
use App\Models\Roles;
use App\Models\Staffdetails;
use App\Models\Siteinfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

class StaffdetailsController extends Controller
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
        if (!config("roles.{$role}.staff_details_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'name'      => 'staff_details.name',
                    'status'    => 'staff_details.status',
                ];
                $sortField   = [
                    'name'     => 'staff_details.name',
                    'status'  => 'staff_details.status',
                    'date_created' => 'staff_details.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'staff_details.created_at',
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

                $records = Staffdetails::getstaffdetails($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Staff details are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No staff details found";
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
                $create_access     = config("roles.{$role}.staff_details_management_access.create");
                $view_access     = config("roles.{$role}.staff_details_management_access.view");
                $edit_access     = config("roles.{$role}.staff_details_management_access.edit");
                $delete_access   = config("roles.{$role}.staff_details_management_access.delete");
                $change_status_access   = config("roles.{$role}.staff_details_management_access.change_status");

                return view('master.staff_details.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.staff_details_management_access.create")) {
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','group_name'];
            $Staffgroups = Staffgroups::getAll($fields,$search);
            $fields = ['id','site_name'];
            $Siteinfo    = Siteinfo::getAll($fields,$search);
            $fields = ['id','role_name'];
            $Roles = Roles::getAll($fields,$search);
            return view('master.staff_details.create',compact('Staffgroups','Roles','Siteinfo'));
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
        $role = session('user_role'); 
        if (!config("roles.{$role}.staff_details_management")) {
            abort(403);
        } else {

            if($request->has('user_name')){
                
                $staff_details_id = $request->get('staff_details_id');
                $fieldValidation  = [ 'name' => ['required','min:2','max:50','unique:staff_details,name,'.$staff_details_id.',uuid']];
                $fieldValidation  = [ 'user_name' => ['required','min:2','max:50','unique:staff_details,user_name,'.$staff_details_id.',uuid']];
                $fieldValidation  = [ 'phone_number' => ['required','numeric','digits_between:1,10','unique:staff_details,phone_number,'.$staff_details_id.',uuid']];
                $fieldValidation  = [ 'email' => ['required','email','min:2','max:100','unique:staff_details,user_name,'.$staff_details_id.',uuid']];
            }
            else{
                $fieldValidation = ['name' => ['required','min:2','max:50','unique:staff_details,name']];
                $fieldValidation = ['user_name'=> ['required','min:2','max:50','unique:staff_details,user_name']];
                $fieldValidation = ['phone_number'=> ['required','numeric','digits_between:1,10','unique:staff_details,phone_number']];
                $fieldValidation = ['email'=> ['required','email','min:2','max:100','unique:staff_details,email']];
            }
            $fieldValidation['user_groups_id'] = ['required'];
            
            if($request->has('staff_details_id')){
            }else{
                $fieldValidation['password'] = ['required','min:6','max:100','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'];
            }
            $fieldValidation['site_id']        = ['required'];
            $fieldValidation['role_id']        = ['required'];
           // $fieldValidation['confirm_password'] = ['required','min:6','max:100','same:password'];

            $errorMessages    = [
                'name.required'             => "Please enter the name",
                'name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('staff_details_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Staffdetails::storeRecords($request);
            }
            else{
                $response   = Staffdetails::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/staff-details/list'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.staff_details_management_access.view")) { 
            abort(403);
        } else {

            $Staffdetails = Staffdetails::getStaffGroupdetails($id);
            if ($Staffdetails) {
                 $data = [
                    'Staffdetails' => $Staffdetails,
                ];
               return view('master.staff_details.view',$data);
            } else {
                $data = [ 'message' => "Invalid staff details"];
                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.staff_details_management_access.edit")) {
            abort(403);
        } else {
            $sfaff_details  = Staffdetails::where(['uuid' => $id])->first();
            if ($sfaff_details) {

                $search = ['status' => 1];
                $fields = ['id','group_name'];
                $Staffgroups = Staffgroups::getAll($fields,$search);
                $fields = ['id','site_name'];
                $Siteinfo    = Siteinfo::getAll($fields,$search);
                $fields = ['id','role_name'];
                $Roles = Roles::getAll($fields,$search);
                $data = [
                    'roles'         => $Roles,
                    'staff_groups'  => $Staffgroups,
                    'site_info'     => $Siteinfo,
                    'details'       => $sfaff_details,
                ];
                return view('master.staff_details.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid Staff details "
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management_access.edit")) {
            abort(403);
        } else {
            $details  = Staffdetails::where(['uuid' => $id])->first();
            $details->status = ($details->status) ? 0 : 1;
            $details->save();

            $data = [
                'redirect_url' => url(route('staff-details-list'))
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
        if (!config("roles.{$role}.product_details_management_access.delete")) {
            abort(403);
        } else {
            $result = Staffdetails::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('staff-details-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "Staff details has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
