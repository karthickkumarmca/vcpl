<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Clients;
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

class ClientsController extends Controller
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
        if (!config("roles.{$role}.clients_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'role_name'      => 'clients.role_name',
                    'status'    => 'clients.status',
                ];
                $sortField   = [
                    'role_name'     => 'clients.role_name',
                    'status'  => 'clients.status',
                    'date_created' => 'clients.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'clients.created_at',
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

                $records = Clients::getclients($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['clients'])) {
                    $statusCode = '200';
                    $message    = "Clients are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No clients found";
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
                $create_access     = config("roles.{$role}.clients_management_access.create");
                $view_access     = config("roles.{$role}.clients_management_access.view");
                $edit_access     = config("roles.{$role}.clients_management_access.edit");
                $delete_access   = config("roles.{$role}.clients_management_access.delete");
                $change_status_access   = config("roles.{$role}.clients_management_access.change_status");

                return view('clients.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.clients_management_access.create")) {
            abort(403);
        } else {
            return view('clients.create');
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.clients_management_access")) {
            abort(403);
        } else {

            $validation = config('field_validation.admin');
            $fieldValidation = [
                'client_name'         => [
                    'required','min:2','max:15'
                ]
            ];

            $errorMessages    = [
                'client_name.required'             => "Please enter the client name",
                'client_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('clients_id'))
            {
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Clients::storeRecords($request);
            }
            else
            {
                $response   = Clients::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('clients-list/'); 

        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.clients_management_access.edit")) {
            abort(403);
        } else {
            // $units  = units::find($id);
            $clients  = Clients::where(['uuid' => $id])->first();
            if ($clients) {
                $data = [
                    'clients' => $clients,
                ];

                return view('clients.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid clients"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.clients_management_access.view")) {
            abort(403);
        } else {
            $clients  = Clients::where(['uuid' => $id])->first();
            if ($clients) {
                $data = [
                    'clients' => $clients,
                ];

                return view('clients.view', $data);
            } else {
                $data = [
                    'message' => "Invalid clients"
                ];

                return view('error_view', $data);
            }
        }
    }
    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['roles_management_access']['change_status']) || $rolesAccess['roles_management_access']['change_status']!=1){
            abort(403);
        } else {
            $clients  = Clients::where(['uuid' => $id])->first();
            $clients->status = ($clients->status) ? 0 : 1;
            $clients->save();

            $data = [
                'redirect_url' => url(route('clients-list'))
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
        if (!config("roles.{$role}.clients_management_access.delete")) {
            abort(403);
        } else {
            $result = Clients::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('clients-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "clients has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
