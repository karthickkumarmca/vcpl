<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
class Roles extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';

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
    public function getEncryptidAttribute($value)
    {
        return encrypt(env('APP_KEY') . $value);
    }
    public static function getroles($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'roles.id',
            'roles.uuid',
            'roles.id AS encryptid',
            'roles.role_name',
            'roles.status as status_id',
            DB::raw('CASE WHEN roles.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(roles.created_at, "%d-%b-%Y %r") AS date_created'),
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
            'roles' => $records,
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

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('roles_id')) {
            $roles = self::where(['uuid' => $request->roles_id])->first();
            $roles->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $roles->updated_at = $request->created_at; 
            }
        } else {
            $roles = new self();
            $roles->created_by = Auth::id();
            $roles->created_at = date('Y-m-d H:i:s');
            $roles->uuid = \Str::uuid()->toString();
            $roles->status = 1;
        }
        $roles->role_name   = $request->role_name;
        $roles->master      = implode(",",$request->master);
        $roles->save();

        $response['status_code'] = '200';
        $response['roles_id'] = $roles->id;
        if ($request->has('units_id')) {
            $response['message'] = "Roles has been updated successfully";
        } else {
            $response['message'] = "Roles has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('roles-list')),
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
}
