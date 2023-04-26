<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Ownership;
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

class OwnershipController extends Controller
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
        if (!config("roles.{$role}.ownership_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'category_name'      => 'ownership.category_name',
                    'status'    => 'ownership.status',
                ];
                $sortField   = [
                    'category_name'     => 'ownership.category_name',
                    'status'  => 'ownership.status',
                    'date_created' => 'ownership.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'ownership.created_at',
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

                $records = Ownership::getList($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Ownership are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Ownership found";
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
                $create_access     = config("roles.{$role}.ownership_management_access.create");
                $view_access     = config("roles.{$role}.ownership_management_access.view");
                $edit_access     = config("roles.{$role}.ownership_management_access.edit");
                $delete_access   = config("roles.{$role}.ownership_management_access.delete");
                $change_status_access   = config("roles.{$role}.ownership_management_access.change_status");

                return view('master.ownership.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.ownership_management_access.create")) {
            abort(403);
        } else {
            return view('master.ownership.create');
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.ownership_management")) {
            abort(403);
        } else {

           
            if($request->has('ownership_id')){
                
                $id = $request->get('ownership_id');
                $fieldValidation = [
                'ownership_name'         => [
                    'required','min:2','max:50','unique:ownership,ownership_name,'.$id.',uuid'
                ],
                'email'         => [
                    'nullable','min:2','max:50','unique:ownership,email,'.$id.',uuid'
                ]
                ];
            }
            else{
                $fieldValidation = [
                'ownership_name'         => [
                    'required','min:2','max:50','unique:ownership,ownership_name'
                ],
                'email'         => [
                    'nullable','min:2','max:50','unique:ownership,email'
                ]
            ];
            }

            $fieldValidation['short_name']  = ['required','min:2','max:50'];
            $fieldValidation['position']    = ['required','min:2','max:50'];
           

            $errorMessages    = [
                'ownership_name.required'             => "Please enter the name",
                'ownership_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('ownership_id')){

                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Ownership::storeRecords($request);
            }
            else{
                $response   = Ownership::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('master/ownership/list'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.ownership_management_access.view")) {
            abort(403);
        } else {
            $ownership  = Ownership::where(['uuid' => $id])->first();
            if ($ownership) {
                $data = [
                    'ownership' => $ownership,
                ];

                return view('master.ownership.view', $data);
            } else {
                $data = [
                    'message' => "Invalid ownership"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.ownership_management_access.edit")) {
            abort(403);
        } else {
            // $categories  = Ownership::find($id);
            $ownership  = Ownership::where(['uuid' => $id])->first();
            if ($ownership) {
                $data = [
                    'ownership' => $ownership,
                ];

                return view('master.ownership.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid ownership"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.ownership_management_access.edit")) {
            abort(403);
        } else {
            $categories  = Ownership::where(['uuid' => $id])->first();
            $categories->status = ($categories->status) ? 0 : 1;
            $categories->save();

            $data = [
                'redirect_url' => url(route('ownership-list'))
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
        if (!config("roles.{$role}.ownership_management_access.delete")) {
            abort(403);
        } else {
            $result = Ownership::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('ownership-list'))
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
