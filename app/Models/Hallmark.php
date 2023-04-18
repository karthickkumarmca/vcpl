<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Hallmark extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hallmark';

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
    public static function getHallmarks($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'hallmark.id',
            'hallmark.uuid',
            'hallmark.id AS encryptid',
            'hallmark.rate',
            'hallmark.status as status_id',
            DB::raw('CASE WHEN hallmark.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(hallmark.created_at, "%d-%b-%Y %r") AS date_created'),
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
            'hallmarks' => $records,
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
        if (!config("roles.{$role}.hallmark_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('hallmark_id')) {
            $hallmark = self::where(['uuid' => $request->hallmark_id])->first();
            $hallmark->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $hallmark->updated_at = $request->created_at; 
            }
        } else {
            $hallmark = new self();
            $hallmark->created_by = Auth::id();
            $hallmark->created_at = date('Y-m-d H:i:s');
        }
        $hallmark->uuid = \Str::uuid()->toString();
        $hallmark->rate = $request->rate;
        $hallmark->status = 1;
        $hallmark->save();

        $response['status_code'] = '200';
        $response['hallmark_id'] = $hallmark->id;
        if ($request->has('hallmark_id')) {
            $response['message'] = "Hallmark has been updated successfully";
        } else {
            $response['message'] = "Hallmark has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('hallmark-list')),
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
    
    public static function getHallmarkCount(){
        $fields = [
            DB::raw('Count(id) AS hallmark_count'),
        ];
        $result = self::select($fields)
        ->get()
        ->toArray();
        return isset($result[0]['hallmark_count'])?$result[0]['hallmark_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('hallmark')->where('id', $id)->update($data);
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
            DB::raw('COUNT(id) AS hallmarks_count')           
        ];
        $query = Self::select($fields);
        $records = $query->first()->toArray();
        return $records;
    }
}
