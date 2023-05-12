<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Categories;
use App\Models\Units;
use App\Models\Productrental;
use App\Models\Productdetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Session;

class ProductrentalController extends Controller
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
        if(!isset($rolesAccess['product_rental_management']) || $rolesAccess['product_rental_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'rent_unit'      => 'product_rental.rent_unit',
                    'status'    => 'product_rental.status',
                ];
                $sortField   = [
                    'rent_unit'     => 'product_rental.rent_unit',
                    'status'  => 'product_rental.status',
                    'date_created' => 'product_rental.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'product_rental.id',
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

                $records = Productrental::getlist($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    if($page==0){
                        $page =1;
                    }
                    if($offset==0){
                        $offset =1;
                    }
                    $i = ($page * $offset)-$offset+1;
                    foreach($records['records'] as $key=>$val){
                        $records['records'][$key]['id'] = $i++;
                    }
                    $statusCode = '200';
                    $message    = "Product details are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No product_rental found";
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
                if(isset($rolesAccess['product_rental_management_access'])){

                    $create_access          = $rolesAccess['product_rental_management_access']['create'];
                    $view_access            = $rolesAccess['product_rental_management_access']['view'];
                    $edit_access            = $rolesAccess['product_rental_management_access']['edit'];
                    $change_status_access   = $rolesAccess['product_rental_management_access']['change_status'];
                }

                return view('master.product_rental.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_rental_management_access']['create']) || $rolesAccess['product_rental_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);

            $search = ['status' => 1];
            $fields = ['id','unit_name'];
            $units = Units::getAll($fields,$search);
            return view('master.product_rental.create',compact('categories','units'));
        }
    }
    public function getProductList(Request $request) {

        $values ='<option value="">----  Select ---</option>';

        $search = ['status' => 1,'category_id'=>$request->get('id')];
        $fields = ['id','product_name'];
        $data = Productdetails::getAll($fields,$search);
        foreach($data as $c){
            $values .='<option value="'.$c['id'].'">'.$c['product_name'].'</option>';
        }
        return $values;
        
    }
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_rental_management_access']['create']) || $rolesAccess['product_rental_management_access']['create']!=1){
            abort(403);
        } else {

           
            $fieldValidation['product_details_id']      = ['required'];
            $fieldValidation['category_id']             = ['required'];
            $fieldValidation['rent_unit']               = ['required'];
            $fieldValidation['unit_id']                 = ['required'];
           

            $errorMessages    = [
                'product_name.required'             => "Please enter the name",
                'product_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('product_rental_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Productrental::storeRecords($request);
            }
            else{
                $response   = Productrental::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/product-rental/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_rental_management_access']['view']) || $rolesAccess['product_rental_management_access']['view']!=1){
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $product_rental  = Productrental::where(['uuid' => $id])->first();

           

            
            if ($product_rental) {

                $search1 = ['status' => 1,'category_id'=>$product_rental->category_id];
                $fields1 = ['id','product_name'];
                $Productdetails = Productdetails::getAll($fields1,$search1);

                $search = ['status' => 1];
                $fields = ['id','unit_name'];
                $units = Units::getAll($fields,$search);

                $data = [
                    'Productdetails'    => $Productdetails,
                    'product_rental'   => $product_rental,
                    'categories'        => $categories,
                    'units'             => $units,
                ];

                return view('master.product_rental.view', $data);
            } else {
                $data = [
                    'message' => "Invalid product_rental"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_rental_management_access']['edit']) || $rolesAccess['product_rental_management_access']['edit']!=1){
            abort(403);
        } else {
            // $product_rental  = Productrental::find($id);
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $product_rental  = Productrental::where(['uuid' => $id])->first();
            if ($product_rental) {

                $search1 = ['status' => 1,'category_id'=>$product_rental->category_id];
                $fields1 = ['id','product_name'];
                $Productdetails = Productdetails::getAll($fields1,$search1);

                $search = ['status' => 1];
                $fields = ['id','unit_name'];
                $units = Units::getAll($fields,$search);

                $data = [
                    'Productdetails'    => $Productdetails,
                    'product_rental' => $product_rental,
                    'categories'     => $categories,
                    'units'     => $units,
                ];

                return view('master.product_rental.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid product_rental"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['product_rental_management_access']['change_status']) || $rolesAccess['product_rental_management_access']['change_status']!=1){
            abort(403);
        } else {
            $product_rental  = Productrental::where(['uuid' => $id])->first();
            $product_rental->status = ($product_rental->status) ? 0 : 1;
            $product_rental->save();

            $data = [
                'redirect_url' => url(route('product-rental-list'))
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
         if(!isset($rolesAccess['product_rental_management_access']['delete']) || $rolesAccess['product_rental_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Productrental::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('product-rental-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "product_rental has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
