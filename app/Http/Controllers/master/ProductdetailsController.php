<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Categories;
use App\Models\Productdetails;
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

class ProductdetailsController extends Controller
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
        if(!isset($rolesAccess['product_details_management']) || $rolesAccess['product_details_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'product_name'      => 'product_details.product_name',
                    'status'    => 'product_details.status',
                ];
                $sortField   = [
                    'product_name'     => 'product_details.product_name',
                    'status'  => 'product_details.status',
                    'date_created' => 'product_details.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'product_details.created_at',
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

                $records = Productdetails::getlist($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Product details are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No product_details found";
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
                if(isset($rolesAccess['product_details_management_access'])){

                    $create_access          = $rolesAccess['product_details_management_access']['create'];
                    $view_access            = $rolesAccess['product_details_management_access']['view'];
                    $edit_access            = $rolesAccess['product_details_management_access']['edit'];
                    $change_status_access   = $rolesAccess['product_details_management_access']['change_status'];
                }

                return view('master.product_details.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_details_management_access']['create']) || $rolesAccess['product_details_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            return view('master.product_details.create',compact('categories'));
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
        if(!isset($rolesAccess['product_details_management_access']['create']) || $rolesAccess['product_details_management_access']['create']!=1){
            abort(403);
        } else {

            if($request->has('product_name')){
                
                $product_details_id = $request->get('product_details_id');
                $fieldValidation = [
                'product_name'         => [
                    'required','min:2','max:50','unique:product_details,product_name,'.$product_details_id.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'product_name'         => [
                    'required','min:2','max:50','unique:product_details,product_name'
                ]
            ];
            }
            $fieldValidation['subcategory_id']      = ['required'];
            $fieldValidation['category_id']         = ['required'];
           

            $errorMessages    = [
                'product_name.required'             => "Please enter the name",
                'product_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('product_details_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Productdetails::storeRecords($request);
            }
            else{
                $response   = Productdetails::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/product-details/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_details_management_access']['view']) || $rolesAccess['product_details_management_access']['view']!=1){
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $product_details  = Productdetails::where(['uuid' => $id])->first();

           

            
            if ($product_details) {

                $search1 = ['status' => 1,'id'=>$product_details->subcategory_id];
                $fields1 = ['id','sub_category_name'];
                $Sub_categories = Sub_categories::getAll($fields1,$search1);
                $data = [
                    'sub_categories'    => $Sub_categories,
                    'product_details'   => $product_details,
                    'categories'        => $categories,
                ];

                return view('master.product_details.view', $data);
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
        if(!isset($rolesAccess['product_details_management_access']['edit']) || $rolesAccess['product_details_management_access']['edit']!=1){
            abort(403);
        } else {
            // $product_details  = Productdetails::find($id);
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $product_details  = Productdetails::where(['uuid' => $id])->first();
            if ($product_details) {

                $search1 = ['status' => 1,'category_id'=>$product_details->category_id];
                $fields1 = ['id','sub_category_name'];
                $Sub_categories = Sub_categories::getAll($fields1,$search1);

                $data = [
                    'sub_categories'    => $Sub_categories,
                    'product_details' => $product_details,
                    'categories'     => $categories,
                ];

                return view('master.product_details.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid product_details"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_details_management_access']['change_status']) || $rolesAccess['product_details_management_access']['change_status']!=1){
            abort(403);
        } else {
            $product_details  = Productdetails::where(['uuid' => $id])->first();
            $product_details->status = ($product_details->status) ? 0 : 1;
            $product_details->save();

            $data = [
                'redirect_url' => url(route('product-details-list'))
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
         if(!isset($rolesAccess['product_details_management_access']['delete']) || $rolesAccess['product_details_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Productdetails::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('product_details-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "product_details has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
