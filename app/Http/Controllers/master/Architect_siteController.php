<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Productdetails;
use App\Models\Architect_site;
use App\Models\Units;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

class Architect_siteController extends Controller
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
        if (!config("roles.{$role}.architect_site_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'vehicle_name'      => 'architect_site.architect_name',
                    'status'    => 'architect_site.status',
                ];
                $sortField   = [
                    'status'  => 'architect_site.status',
                    'date_created' => 'architect_site.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'architect_site.created_at',
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

                $records = Architect_site::get_materials($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Data are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No architect_site found";
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
                $create_access     = config("roles.{$role}.architect_site_management_access.create");
                $view_access     = config("roles.{$role}.architect_site_management_access.view");
                $edit_access     = config("roles.{$role}.architect_site_management_access.edit");
                $delete_access   = config("roles.{$role}.architect_site_management_access.delete");
                $change_status_access   = config("roles.{$role}.architect_site_management_access.change_status");

                return view('master.architect_site.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.architect_site_management_access.create")) {
            abort(403);
        } else {
            $search = ['status' => 1,'category_id'=>6];
            $fields = ['id','product_name'];
            $categories = Productdetails::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','unit_name'];
            $units = Units::getAll($fields1,$search1);
            return view('master.architect_site.create',compact('categories','units'));
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.architect_site_management")) {
            abort(403);
        } else {

            if($request->has('architect_name_id')){
                
                $architect_name_id = $request->get('architect_name_id');
                $fieldValidation = [
                'architect_name'         => [
                    'required','min:2','max:15','unique:architect_site,architect_name,'.$architect_name_id.',uuid'
                ]
                ];
            }
            else{
                 $fieldValidation = [
                'architect_name'         => [
                    'required','min:2','max:15','unique:architect_site,architect_name'
                ]
            ];
            }
           

            $errorMessages    = [
                'lorry_materials.required'             => "Please enter the name",
                'lorry_materials.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('architect_name_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Architect_site::storeRecords($request);
            }
            else{
                $response   = Architect_site::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/vehicle-materials/list'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.architect_site_management_access.view")) {
            abort(403);
        } else {

            $search = ['status' => 1,'category_id'=>6];
            $fields = ['id','product_name'];
            $categories = Productdetails::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','unit_name'];
            $units = Units::getAll($fields1,$search1);

            $architect_site  = Architect_site::where(['uuid' => $id])->first();
            if ($architect_site) {

               
                $data = [
                    'units'         => $units,
                    'architect_site' => $architect_site,
                    'categories'     => $categories,
                ];

                return view('master.architect_site.view', $data);
            } else {
                $data = [
                    'message' => "Invalid architect_site"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.architect_site_management_access.edit")) {
            abort(403);
        } else {
            $search = ['status' => 1,'category_id'=>6];
            $fields = ['id','product_name'];
            $categories = Productdetails::getAll($fields,$search);

            $search1 = ['status' => 1];
            $fields1 = ['id','unit_name'];
            $units = Units::getAll($fields1,$search1);

            $architect_site  = Architect_site::where(['uuid' => $id])->first();
            if ($architect_site) {

               
                $data = [
                    'units'         => $units,
                    'architect_site' => $architect_site,
                    'categories'     => $categories,
                ];

                return view('master.architect_site.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid architect_site"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.architect_site_management_access.edit")) {
            abort(403);
        } else {
            $architect_site  = Architect_site::where(['uuid' => $id])->first();
            $architect_site->status = ($architect_site->status) ? 0 : 1;
            $architect_site->save();

            $data = [
                'redirect_url' => url(route('vehicle-materials-list'))
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
        if (!config("roles.{$role}.architect_site_management_access.delete")) {
            abort(403);
        } else {
            $result = Architect_site::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('vehicle-materials-list'))
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
