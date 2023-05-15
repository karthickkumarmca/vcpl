<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Property_categories;
use App\Models\Property_name;
use App\Models\Ownership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Session;

class PropertynameController extends Controller
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
        if(!isset($rolesAccess['property_name_management']) || $rolesAccess['property_name_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'property_name'      => 'property_name.property_name',
                    'ownership_name'      => 'property_category.id',
                    'category_name'      => 'property_name.ownership_id',
                    'status'    => 'property_name.status',
                ];
                $sortField   = [
                    'property_name'     => 'property_name.property_name',
                    'status'  => 'property_name.status',
                    'date_created' => 'property_name.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'property_name.created_at',
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

                $records = Property_name::getproperty_name($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "data are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Property name found";
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
                if(isset($rolesAccess['property_name_management_access'])){

                    $search1 = ['status' => 1];
                    $fields1 = ['id as value','ownership_name as label'];
                    $ownership = Ownership::getAll($fields1,$search1);

                    $search = ['status' => 1];
                    $fields = ['id  as value','category_name  as label'];
                    $categories = Property_categories::getAll($fields,$search);

                    $create_access          = $rolesAccess['property_name_management_access']['create'];
                    $view_access            = $rolesAccess['property_name_management_access']['view'];
                    $edit_access            = $rolesAccess['property_name_management_access']['edit'];
                    $change_status_access   = $rolesAccess['property_name_management_access']['change_status'];
                    $delete_access   = $rolesAccess['property_name_management_access']['delete'];
                }

                return view('master.property_name.list', compact('statuses','ownership','categories', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['property_name_management_access']['create']) || $rolesAccess['property_name_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Property_categories::getAll($fields,$search);

            $ownership = Ownership::getOwnerName();
            return view('master.property_name.create',compact('categories','ownership'));
        }
    }
    public function store(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['property_name_management_access']['create']) || $rolesAccess['property_name_management_access']['create']!=1){
            abort(403);
        } else {

            if($request->has('property_name_id')){
                
                $property_name_id = $request->get('property_name_id');
                $fieldValidation = [
                'property_name'         => [
                    'required','min:2','max:50','unique:property_name,property_name,'.$property_name_id.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'property_name'         => [
                    'required','min:2','max:50','unique:property_name,property_name'
                ]
            ];
            }
            $fieldValidation['category_id']     = ['required'];
            $fieldValidation['ownership_id']    = ['required'];
           

            $errorMessages    = [
                'property_name.required'             => "Please enter the name",
                'property_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('property_name_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Property_name::storeRecords($request);
            }
            else{
                $response   = Property_name::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/property-name/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['property_name_management_access']['view']) || $rolesAccess['property_name_management_access']['view']!=1){
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Property_categories::getAll($fields,$search);

            $ownership = Ownership::getOwnerName();

            $property_name  = Property_name::where(['uuid' => $id])->first();
            if ($property_name) {
                $data = [
                    'property_name'     => $property_name,
                    'categories'        => $categories,
                    'ownership'         => $ownership,
                ];

                return view('master.property_name.view', $data);
            } else {
                $data = [
                    'message' => "Invalid property_name"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['property_name_management_access']['edit']) || $rolesAccess['property_name_management_access']['edit']!=1){
            abort(403);
        } else {
            // $property_name  = property_name::find($id);
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Property_categories::getAll($fields,$search);
            $property_name  = Property_name::where(['uuid' => $id])->first();
            if ($property_name) {

                $ownership = Ownership::getOwnerName();
                $data = [
                    'ownership'         => $ownership,
                    'property_name' => $property_name,
                    'categories'     => $categories,
                ];

                return view('master.property_name.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid property_name"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
         if(!isset($rolesAccess['property_name_management_access']['change_status']) || $rolesAccess['property_name_management_access']['change_status']!=1){
            abort(403);
        } else {
            $property_name  = Property_name::where(['uuid' => $id])->first();
            $property_name->status = ($property_name->status) ? 0 : 1;
            $property_name->save();

            $data = [
                'redirect_url' => url(route('property-name-list'))
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
         if(!isset($rolesAccess['property_name_management_access']['delete']) || $rolesAccess['property_name_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Property_name::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('property-name-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "property_name has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
