<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Units extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

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
    public static function getunits($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'units.id',
            'units.uuid',
            'units.id AS encryptid',
            'units.unit_name',
            'units.status as status_id',
            DB::raw('CASE WHEN units.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(units.created_at, "%d-%b-%Y %r") AS date_created'),
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
            'units' => $records,
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
        if (!config("roles.{$role}.units_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('units_id')) {
            $units = self::where(['uuid' => $request->units_id])->first();
            $units->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $units->updated_at = $request->created_at; 
            }
        } else {
            $units = new self();
            $units->created_by = Auth::id();
            $units->created_at = date('Y-m-d H:i:s');
            $units->uuid = \Str::uuid()->toString();
            $units->status = 1;
        }
        $units->unit_name = $request->unit_name;
        $units->save();

        $response['status_code'] = '200';
        $response['units_id'] = $units->id;
        if ($request->has('units_id')) {
            $response['message'] = "units has been updated successfully";
        } else {
            $response['message'] = "units has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('units-list')),
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
    
    public static function getunitsCount(){
        $fields = [
            DB::raw('Count(id) AS units_count'),
        ];
        $result = self::select($fields)
        ->get()
        ->toArray();
        return isset($result[0]['units_count'])?$result[0]['units_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('units')->where('id', $id)->update($data);
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
            DB::raw('COUNT(id) AS unitss_count')           
        ];
        $query = Self::select($fields);
        $records = $query->first()->toArray();
        return $records;
    }
}
