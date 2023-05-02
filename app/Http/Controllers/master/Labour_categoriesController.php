<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Labour_categories;
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

class Labour_categoriesController extends Controller
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
        if(!isset($rolesAccess['labour_categories_management']) || $rolesAccess['labour_categories_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'category_name'      => 'labour_category.category_name',
                    'status'    => 'labour_category.status',
                ];
                $sortField   = [
                    'category_name'     => 'labour_category.category_name',
                    'status'  => 'labour_category.status',
                    'date_created' => 'labour_category.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'labour_category.created_at',
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

                $records = Labour_categories::getcategories($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['categories'])) {
                    $statusCode = '200';
                    $message    = "categories are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No categories found";
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
                if(isset($rolesAccess['labour_categories_management_access'])){

                    $create_access          = $rolesAccess['labour_categories_management_access']['create'];
                    $view_access            = $rolesAccess['labour_categories_management_access']['view'];
                    $edit_access            = $rolesAccess['labour_categories_management_access']['edit'];
                    $change_status_access   = $rolesAccess['labour_categories_management_access']['change_status'];
                    $delete_access   = $rolesAccess['labour_categories_management_access']['delete'];
                }

                return view('master.labour_categories.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_categories_management_access']['create']) || $rolesAccess['labour_categories_management_access']['create']!=1){
            abort(403);
        } else {
            return view('master.labour_categories.create');
        }
    }
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_categories_management_access']['create']) || $rolesAccess['labour_categories_management_access']['create']!=1){
            abort(403);
        } else {

           
            if($request->has('categories_id')){
                
                $categories_id = $request->get('categories_id');
                $fieldValidation = [
                'category_name'         => [
                    'required','min:2','max:50','unique:labour_category,category_name,'.$categories_id.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'category_name'         => [
                    'required','min:2','max:50','unique:labour_category,category_name'
                ]
            ];
            }
           

            $errorMessages    = [
                'category_name.required'             => "Please enter the name",
                'category_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('categories_id'))
            {
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Labour_categories::storeRecords($request);
            }
            else
            {
                $response   = Labour_categories::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('master/labour-categories/list'); 

        }
    }

    public function view($id)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_categories_management_access']['view']) || $rolesAccess['labour_categories_management_access']['view']!=1){
            abort(403);
        } else {
            $categories  = Labour_categories::where(['uuid' => $id])->first();
            if ($categories) {
                $data = [
                    'categories' => $categories,
                ];

                return view('master.labour_categories.view', $data);
            } else {
                $data = [
                    'message' => "Invalid categories"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['labour_categories_management_access']['edit']) || $rolesAccess['labour_categories_management_access']['edit']!=1){
            abort(403);
        } else {
            // $categories  = Labour_categories::find($id);
            $categories  = Labour_categories::where(['uuid' => $id])->first();
            if ($categories) {
                $data = [
                    'categories' => $categories,
                ];

                return view('master.labour_categories.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid categories"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
         if(!isset($rolesAccess['labour_categories_management_access']['change_status']) || $rolesAccess['labour_categories_management_access']['change_status']!=1){
            abort(403);
        } else {
            $categories  = Labour_categories::where(['uuid' => $id])->first();
            $categories->status = ($categories->status) ? 0 : 1;
            $categories->save();

            $data = [
                'redirect_url' => url(route('labour-categories-list'))
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
         if(!isset($rolesAccess['labour_categories_management_access']['delete']) || $rolesAccess['labour_categories_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Labour_categories::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('labour-categories-list'))
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
