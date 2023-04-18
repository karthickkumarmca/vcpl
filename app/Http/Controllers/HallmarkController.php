<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Hallmark;
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

class HallmarkController extends Controller
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
        if (!config("roles.{$role}.hallmark_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'rate'      => 'hallmark.rate',
                    'status'    => 'hallmark.status',
                ];
                $sortField   = [
                    'rate'     => 'hallmark.rate',
                    'status'  => 'hallmark.status',
                    'date_created' => 'hallmark.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'hallmark.created_at',
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

                $records = Hallmark::getHallmarks($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['hallmarks'])) {
                    $statusCode = '200';
                    $message    = "Hallmarks are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Hallmarks found";
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
                $create_access     = config("roles.{$role}.hallmark_management_access.create");
                $view_access     = config("roles.{$role}.hallmark_management_access.view");
                $edit_access     = config("roles.{$role}.hallmark_management_access.edit");
                $delete_access   = config("roles.{$role}.hallmark_management_access.delete");
                $change_status_access   = config("roles.{$role}.hallmark_management_access.change_status");

                return view('hallmarks.hallmarks-list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.hallmark_management_access.create")) {
            abort(403);
        } else {
            return view('hallmarks.create');
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.hallmark_management")) {
            abort(403);
        } else {

            $validation = config('field_validation.admin');
            $fieldValidation = [
                'rate'         => [
                    'required','numeric','between:0.01,100.00','regex:/^\d{0,4}(\.\d{0,2})?$/i'
                ]
            ];
            if (App::environment() != "local") {
                $fieldValidation['g-recaptcha-response'] = ['required','captcha'];
            }

            $errorMessages    = [
                'rate.required'             => "Please enter the name",
                'rate.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('hallmark_id'))
            {
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Hallmark::storeRecords($request);
            }
            else
            {
                $response   = Hallmark::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('hallmark-list/'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.hallmark_management_access.view")) {
            abort(403);
        } else {
            $hallmark  = Hallmark::where(['uuid' => $id])->first();
            if ($hallmark) {
                $data = [
                    'hallmark' => $hallmark,
                ];

                return view('hallmarks.view', $data);
            } else {
                $data = [
                    'message' => "Invalid hallmark"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.hallmark_management_access.edit")) {
            abort(403);
        } else {
            // $hallmark  = Hallmark::find($id);
            $hallmark  = Hallmark::where(['uuid' => $id])->first();
            if ($hallmark) {
                $data = [
                    'hallmark' => $hallmark,
                ];

                return view('hallmarks.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid hallmark"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.hallmark_management_access.edit")) {
            abort(403);
        } else {
            $hallmark  = Hallmark::where(['uuid' => $id])->first();
            $hallmark->status = ($hallmark->status) ? 0 : 1;
            $hallmark->save();

            $data = [
                'redirect_url' => url(route('hallmark-list'))
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
        if (!config("roles.{$role}.hallmark_management_access.delete")) {
            abort(403);
        } else {
            $result = Hallmark::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('hallmark-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "Hallmark has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
