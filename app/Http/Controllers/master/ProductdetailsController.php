<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Categories;
use App\Models\product_details;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

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
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'product_name'      => 'subcategories.product_name',
                    'status'    => 'subcategories.status',
                ];
                $sortField   = [
                    'product_name'     => 'subcategories.product_name',
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

                $records = product_details::getsubcategories($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Sub categories are retrieved Successfully";
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

                $role = session('user_role');
                $create_access     = config("roles.{$role}.product_details_management_access.create");
                $view_access     = config("roles.{$role}.product_details_management_access.view");
                $edit_access     = config("roles.{$role}.product_details_management_access.edit");
                $delete_access   = config("roles.{$role}.product_details_management_access.delete");
                $change_status_access   = config("roles.{$role}.product_details_management_access.change_status");

                return view('master.product_details.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management_access.create")) {
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            return view('master.product_details.create',compact('categories'));
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management")) {
            abort(403);
        } else {

            if($request->has('product_name')){
                
                $product_name = $request->get('product_name');
                $fieldValidation = [
                'product_name'         => [
                    'required','min:2','max:50','unique:product_details,product_name,'.$product_name.',uuid'
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
                $response   = product_details::storeRecords($request);
            }
            else{
                $response   = product_details::storeRecords($request); 
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
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management_access.view")) {
            abort(403);
        } else {

            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $product_details  = product_details::where(['uuid' => $id])->first();
            if ($product_details) {
                $data = [
                    'product_details' => $product_details,
                    'categories'     => $categories,
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
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management_access.edit")) {
            abort(403);
        } else {
            // $product_details  = product_details::find($id);
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            $product_details  = product_details::where(['uuid' => $id])->first();
            if ($product_details) {
                $data = [
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
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management_access.edit")) {
            abort(403);
        } else {
            $product_details  = product_details::where(['uuid' => $id])->first();
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
        $role = session('user_role');
        if (!config("roles.{$role}.product_details_management_access.delete")) {
            abort(403);
        } else {
            $result = product_details::where('uuid', $id)->delete();

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
