<?php

namespace App\Http\Controllers\master;

use App\Admin;
use App\Models\Categories;
use App\Models\Messageheader;
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

class MessageheaderController extends Controller
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
        if(!isset($rolesAccess['message_header_management']) || $rolesAccess['message_header_management']!=1){
            abort(403);
        } else {
            if ($request->has('request_type')) {
                $searchField = [
                    'name'      => 'message_header.name',
                    'description'      => 'message_header.description',
                    'status'    => 'message_header.status',
                ];
                $sortField   = [
                    'name'     => 'message_header.name',
                    'status'  => 'message_header.status',
                    'date_created' => 'message_header.created_at'
                ];
                $search_filter = [];
                $sort = [
                    'field' => 'message_header.id',
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

                $records = Messageheader::getlist($page, $offset, $sort, $search_filter);
                //print_r($records);exit;

                if (!empty($records['records'])) {
                    $statusCode = '200';
                    $message    = "Product details are retrieved Successfully";
                    $data       = $records;
                } else {
                    $statusCode = '400';
                    $message    = "No message_header found";
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
                if(isset($rolesAccess['message_header_management_access'])){

                    $create_access          = $rolesAccess['message_header_management_access']['create'];
                    $view_access            = $rolesAccess['message_header_management_access']['view'];
                    $edit_access            = $rolesAccess['message_header_management_access']['edit'];
                    $change_status_access   = $rolesAccess['message_header_management_access']['change_status'];
                }

                return view('master.message_header.list', compact('statuses', 'create_access', 'view_access', 'edit_access', 'delete_access', 'change_status_access'));
            }
        }
    }

    public function create(Request $request)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['message_header_management_access']['create']) || $rolesAccess['message_header_management_access']['create']!=1){
            abort(403);
        } else {
            $search = ['status' => 1];
            $fields = ['id','category_name'];
            $categories = Categories::getAll($fields,$search);
            return view('master.message_header.create',compact('categories'));
        }
    }
   
    public function store(Request $request)
    {
       $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['message_header_management_access']['create']) || $rolesAccess['message_header_management_access']['create']!=1){
            abort(403);
        } else {

           
            $fieldValidation['name']                    = ['required','min:1','max:100'];
            $fieldValidation['description']             = ['required','min:1','max:128'];
           

            $errorMessages    = [
                'product_name.required'             => "Please enter the name",
                'product_name.regex'                => "Should include only Two Decimal Places",
            ];

            $validator = app('validator')->make($request->all(), $fieldValidation, $errorMessages);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator);
            }

            if($request->has('message_header_id')){
                $request['created_at']=date('Y-m-d H:i:s');
                $response   = Messageheader::storeRecords($request);
            }
            else{
                $response   = Messageheader::storeRecords($request); 
            }

            $statusCode = $response['status_code'];
            $error      = isset($response['error']) ? $response['error'] : (object)[];
            $message    = $response['message'];
            $data       = isset($response['data']) ? $response['data'] : (object)[];

            return redirect('master/message-header/list'); 

        }
    }

    public function view($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['message_header_management_access']['view']) || $rolesAccess['message_header_management_access']['view']!=1){
            abort(403);
        } else {

            
            $message_header  = Messageheader::where(['uuid' => $id])->first();
            
            if ($message_header) {

                $data = [
                    'message_header'   => $message_header,
                ];

                return view('master.message_header.view', $data);
            } else {
                $data = [
                    'message' => "Invalid message_header"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function edit($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['message_header_management_access']['edit']) || $rolesAccess['message_header_management_access']['edit']!=1){
            abort(403);
        } else {
            $message_header  = Messageheader::where(['uuid' => $id])->first();
            if ($message_header) {

                $data = [
                    'message_header' => $message_header,
                ];

                return view('master.message_header.edit', $data);
            } else {
                $data = [
                    'message' => "Invalid message_header"
                ];

                return view('error_view', $data);
            }
        }
    }

    public function updateStatus($id)
    {
        $rolesAccess = Session::get('role_access');
        if(!isset($rolesAccess['message_header_management_access']['change_status']) || $rolesAccess['message_header_management_access']['change_status']!=1){
            abort(403);
        } else {
            $message_header  = Messageheader::where(['uuid' => $id])->first();
            $message_header->status = ($message_header->status) ? 0 : 1;
            $message_header->save();

            $data = [
                'redirect_url' => url(route('message-header-list'))
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
         if(!isset($rolesAccess['message_header_management_access']['delete']) || $rolesAccess['message_header_management_access']['delete']!=1){
            abort(403);
        } else {
            $result = Messageheader::where('uuid', $id)->delete();

            $data = [
                'redirect_url' => url(route('message-header-list'))
            ];

            $statusCode = '200';
            $error      = (object)[];
            $message    = "message_header has been deleted Successfully";

            return response()->json([
                'message' => $message,
                'data'    => $data,
                'error'   => $error
            ], $statusCode);
        }
    }
}
