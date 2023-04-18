<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    public function getJWTIdentifier()
    {
        return $this->getKey(); //return $this->getAttribute('mobile_number');
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
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
    public function getPhotoIdAttribute($value)
    {
        if (!empty($value)) {
            $imagelink = !empty($value) ? Helper::getImageLink($value) : "";
            return $imagelink;
        } else {
            return $value;
        }
    }
    public function getUserencryptAttribute($value)
    {
        return encrypt(env('APP_KEY') . $value);
    }
    public static function getUsers($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'users.id',
            'users.uuid',
            'users.id AS userencrypt',
            'users.name',
            'users.email',
            //'users.testing_centre_id',
            'users.status as status_id',
            'users.user_type as user_role',
            DB::raw('CASE user_type
                WHEN 1 THEN "Super Admin"
                WHEN 2 THEN "Users"
                ELSE "-"
                END user_type,
                CASE WHEN users.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(users.created_at, "%d-%b-%Y %r") AS date_created'),
        ];
        $query = User::select($fields);

        if ($search_filter) {
            $query->where($search_filter);
        }

        $totalItems = $query->where('user_type', '2')->count();
        $totalPages = ceil($totalItems / $offset);

        if ($page <= 0) {
            $page = 0;
        } elseif ($page > $totalPages) {
            $page = $totalPages - 1;
        } else {
            $page--;
        }

        $records = $query->orderBy($sort['field'], $sort['order'])
        ->where('user_type', '2')
        ->offset($page * $offset)
        ->limit($offset)
        ->get()
        ->toArray();

        return [
            'users' => $records,
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
    public static function storeRecords(Request $request,$path)
    {
        $role = session('user_role');
        if (!config("roles.{$role}.user_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('user_id')) {
            $user = self::where(['uuid' => $request->user_id])->first();
            $user->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $user->password = bcrypt($request->password);
                $user->updated_at = $request->created_at; 
            }
        } else {
            $user = new self();
            $user->password = bcrypt($request->password);
            $user->created_by = Auth::id();
            $user->created_at = date('Y-m-d H:i:s');
        }
        $user->uuid = \Str::uuid()->toString();
        $user->name = strip_tags($request->name);
        $user->email = strip_tags($request->email);
        $user->phonenumber = strip_tags($request->phone);
        $user->user_type = 2;
        if ($path) {
            $user->user_image = $path;
        }
        
        $user->status = 1;
        $user->save();

        $response['status_code'] = '200';
        $response['user_id'] = $user->id;
        if ($request->has('user_id')) {
            $response['message'] = "User has been updated successfully";
        } else {
            $response['message'] = "User has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('user-list')),
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
        $result = User::select($fields)
        ->where('user_type',2)
        ->get()
        ->toArray();
        return isset($result[0]['customer_count'])?$result[0]['customer_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('users')->where('id', $id)->update($data);
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
            DB::raw('SUM(CASE WHEN user_type = 2 THEN 1 ELSE 0 END) AS users_count')           
        ];
        $query = Self::select($fields)->where('user_type',2);
        $records = $query->first()->toArray();
        return $records;
    }
}
