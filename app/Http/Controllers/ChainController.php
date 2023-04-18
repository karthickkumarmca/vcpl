<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Chain;
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

class ChainController extends Controller
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
        if (!config("roles.{$role}.chain_management")) {
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'chain'      => 'chain.chain',
                    'hallmark_id'=> 'chain.hallmark_id',
                    'status'    => 'chain.status',
                ];
                $sortField   = [
                    'chain'     => 'chain.chain',
                    'hallmark_id'=> 'chain.hallmark_id',
                    'status'  => 'chain.status',
                    'date_created' => 'chain.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'chain.created_at',
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

                $records = Chain::getChains($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['chains'])) {
                    $statusCode = '200';
                    $message    = "Chains are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No Chains found";
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
                $search = ['status' => 1];
                $fields = ['id as value','rate as label'];
                $hallmarks = Hallmark::getAll($fields,$search);

                $role = session('user_role');
                $create_access     = config("roles.{$role}.chain_management_access.create");
                $view_access     = config("roles.{$role}.chain_management_access.view");
                $edit_access     = config("roles.{$role}.chain_management_access.edit");
                $delete_access   = config("roles.{$role}.chain_management_access.delete");
                $change_status_access   = config("roles.{$role}.chain_management_access.change_status");

                return view('chains.chains-list', compact('statuses','hallmarks', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.chain_management_access.create")) {
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','rate'];
            $hallmarks = Hallmark::getAll($fields,$search);
            return view('chains.create',compact('hallmarks'));
        }
    }
    public function store(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.chain_management")) {
            abort(403);
        } else {

            $validation = config('field_validation.admin');
            $fieldValidation = [
                'chain'         => [
                    'required','min:6','max:128'
                ],
                'hallmark_id'   => [
                    'required'
                ]
            ];
            if (App::environment() != "local") {
                $fieldValidation['g-recaptcha-response'] = ['required','captcha'];
            }

            $errorMessages    = [
                'rate.required'             => "Please enter the name",
                'hallmark_id.required'      => "Please choose the hallmark",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('chain_id'))
            {
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Chain::storeRecords($request);
            }
            else
            {
                $response   = Chain::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];


            return redirect('chain-list/'); 

        }
    }

    public function view($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.chain_management_access.view")) {
            abort(403);
        } else {
            $chain  = Chain::getChainbyUuid($id);
            if ($chain) {
                $data = [
                    'chain' => $chain,
                ];

                return view('chains.view', $data);
            } else {
                $data = [
                    'message' => "Invalid chain"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.chain_management_access.edit")) {
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','rate'];
            $hallmarks = Hallmark::getAll($fields,$search);

            // $chain  = chain::find($id);
            $chain  = Chain::where(['uuid' => $id])->first();
            if ($chain) {
                $data = [
                    'chain' => $chain,
                    'hallmarks' => $hallmarks,
                ];

                return view('chains.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid chain"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.chain_management_access.edit")) {
            abort(403);
        } else {
            $chain  = Chain::where(['uuid' => $id])->first();
            $chain->status = ($chain->status) ? 0 : 1;
            $chain->save();

            $data = [
                'redirect_url' => url(route('chain-list'))
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
        if (!config("roles.{$role}.chain_management_access.delete")) {
            abort(403);
        } else {
            $result = Chain::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('chain-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "Chain has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
