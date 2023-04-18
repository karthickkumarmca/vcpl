<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $guarded = [];
    /**
     * Get list of admin users
     *
     * @param  int   $page   current page
     * @param  int   $offset page limit
     * @param  Array   $sort sort
     * @param Array $search_filter
     *
     * @return array $response
     */
    public function getCustomerencryptAttribute($value)
    {
        return encrypt(env('APP_KEY') . $value);
    }
    public static function getCustomers($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'customers.id',
            'customers.uuid',
            'customers.id AS customerencrypt',
            'customers.name',
            'customers.email',
            'customers.status as status_id',
            DB::raw('CASE WHEN customers.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(customers.created_at, "%d-%b-%Y %r") AS date_created'),
        ];
        $query = self::select($fields);

        if ($search_filter) {
            $query->where($search_filter);
        }

        $totalItems = $query->count();
        $totalPages = ceil($totalItems / $offset);

        if ($page <= 0) {
            $page = 0;
        } elseif ($page > $totalPages) {
            $page = $totalPages - 1;
        } else {
            $page--;
        }

        $records = $query->orderBy($sort['field'], $sort['order'])
        ->offset($page * $offset)
        ->limit($offset)
        ->get()
        ->toArray();

        return [
            'customers' => $records,
            'current_page' => $page + 1,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
        ];
    }

    /**
     * Store admin details
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Array $response
     */
    public static function storeRecords(Request $request)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.customer_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('customer_id')) {
            $customer = self::where(['uuid' => $request->customer_id])->first();
            $customer->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $customer->updated_at = $request->created_at; 
            }
        } else {
            $customer = new self();
            $customer->created_by = Auth::id();
            $customer->created_at = date('Y-m-d H:i:s');
        }
        $customer->uuid = \Str::uuid()->toString();
        $customer->name = strip_tags($request->name);
        $customer->email = strip_tags($request->email);
        $customer->phonenumber = strip_tags($request->phone);

        $customer->status = 1;
        $customer->save();

        $response['status_code'] = '200';
        $response['customer_id'] = $customer->id;
        if ($request->has('customer_id')) {
            $response['message'] = "Customer has been updated successfully";
        } else {
            $response['message'] = "Customer has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('customer-list')),
        ];

        return $response;
    }
    public static function getAll(array $fields, array $filter = []): array
    {
        return self::select($fields)
        ->where($filter)
        ->get()
        ->toArray();
    }
    
    public static function getCustomerCount(){
        $fields = [
            DB::raw('Count(id) AS customer_count'),
        ];
        $result = self::select($fields)
        ->get()
        ->toArray();
        return isset($result[0]['customer_count'])?$result[0]['customer_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('customers')->where('id', $id)->update($data);
        return $id;
    }
    public static function updateDetails($where,$updateDetails)
    {
        self::where($where)->update($updateDetails);
        return true;
    }
    public static function getdashboardcount()
    {

        $fields = [
            DB::raw('COUNT(*) AS total_count'),
            DB::raw('COUNT(id) AS customers_count')           
        ];
        $query = Self::select($fields);
        $records = $query->first()->toArray();
        return $records;
    }
}
