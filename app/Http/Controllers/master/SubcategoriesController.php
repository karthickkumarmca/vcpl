<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Categories;
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

class SubcategoriesController extends Controller
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
        if(!isset($rolesAccess['sub_categories_management']) || $rolesAccess['sub_categories_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'sub_category_name'      => 'subcategories.sub_category_name',
                    'status'            => 'subcategories.status',
                    'category_name'     => 'subcategories.category_id',
                ];
                $sortField   = [
                    'sub_category_name'     => 'subcategories.sub_category_name',
                    'status'  => 'subcategories.status',
                    'date_created' => 'subcategories.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'subcategories.created_at',
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

                $records = Sub_categories::getsubcategories($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Sub categories are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No sub_categories found";
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
                if(isset($rolesAccess['sub_categories_management_access'])){

                    $search = ['status' => 1];
                    $fields = ['id as value','category_name as label'];
                    $categories = Categories::getAll($fields,$search);

                    $create_access          = $rolesAccess['sub_categories_management_access']['create'];
                    $view_access            = $rolesAccess['sub_categories_management_access']['view'];
                    $edit_access            = $rolesAccess['sub_categories_management_access']['edit'];
                    $change_status_access   = $rolesAccess['sub_categories_management_access']['change_status'];
                }

                return view('master.sub_categories.list', compact('statuses','categories', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['sub_categories_management_access']['create']) || $rolesAccess['sub_categories_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            return view('master.sub_categories.create',compact('categories'));
        }
    }
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['sub_categories_management_access']['create']) || $rolesAccess['sub_categories_management_access']['create']!=1){
            abort(403);
        } else {

            if($request->has('sub_category_name')){
                
                $sub_category_name = $request->get('sub_category_name');
                $fieldValidation = [
                'sub_category_name'         => [
                    'required','min:2','max:50','unique:subcategories,sub_category_name,'.$sub_category_name.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'sub_category_name'         => [
                    'required','min:2','max:50','unique:subcategories,sub_category_name'
                ]
            ];
            }
           

            $errorMessages    = [
                'sub_category_name.required'             => "Please enter the name",
                'sub_category_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('sub_categories_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Sub_categories::storeRecords($request);
            }
            else{
                $response   = Sub_categories::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/sub-categories/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['sub_categories_management_access']['view']) || $rolesAccess['sub_categories_management_access']['view']!=1){
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $sub_categories  = Sub_categories::where(['uuid' => $id])->first();
            if ($sub_categories) {
                $data = [
                    'sub_categories' => $sub_categories,
                    'categories'     => $categories,
                ];

                return view('master.sub_categories.view', $data);
            } else {
                $data = [
                    'message' => "Invalid sub_categories"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['sub_categories_management_access']['edit']) || $rolesAccess['sub_categories_management_access']['edit']!=1){
            abort(403);
        } else {
            // $sub_categories  = Sub_categories::find($id);
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $sub_categories  = Sub_categories::where(['uuid' => $id])->first();
            if ($sub_categories) {
                $data = [
                    'sub_categories' => $sub_categories,
                    'categories'     => $categories,
                ];

                return view('master.sub_categories.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid sub_categories"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['sub_categories_management_access']['change_status']) || $rolesAccess['sub_categories_management_access']['change_status']!=1){
            abort(403);
        } else {
            $sub_categories  = Sub_categories::where(['uuid' => $id])->first();
            $sub_categories->status = ($sub_categories->status) ? 0 : 1;
            $sub_categories->save();

            $data = [
                'redirect_url' => url(route('sub-categories-list'))
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
         if(!isset($rolesAccess['sub_categories_management_access']['delete']) || $rolesAccess['sub_categories_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Sub_categories::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('sub_categories-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "sub_categories has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
