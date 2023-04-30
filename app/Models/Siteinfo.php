<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Siteinfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'site_info';

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

    public static function getlist($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'site_info.id',
            'site_info.uuid',
            'site_info.id AS encryptid',
            'site_info.site_name',
            'site_info.site_location',
            'site_info.status as status_id',
            'se.name AS site_engineer_name',
            'sc.name AS sub_contractor_name',
            'sk.name AS store_keeper_name',
            DB::raw('CASE WHEN site_info.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(site_info.created_at, "%d-%b-%Y %r") AS date_created'),
        ];
        $query = self::select($fields)->leftjoin('staff_details as se','se.id','site_info.site_engineer_id')
        ->leftjoin('staff_details as sc','sc.id','site_info.sub_contractor_id')
        ->leftjoin('staff_details as sk','sk.id','site_info.store_keeper_id');

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
            'records' => $records,
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
        if (!config("roles.{$role}.site_info_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('site_info_id')) {
            $data = self::where(['uuid' => $request->site_info_id])->first();
            $data->updated_by = Auth::id();
            if(isset($request->created_at)) {
                $data->updated_at = $request->created_at; 
            }
        } else {
            $data = new self();
            $data->created_by = Auth::id();
            $data->created_at = date('Y-m-d H:i:s');
            $data->uuid = \Str::uuid()->toString();
            $data->status = 1;
        }
       
        $data->site_name         = $request->site_name;
        $data->site_location     = $request->site_location;
        $data->site_engineer_id  = $request->site_engineer_id;
        $data->sub_contractor_id = $request->sub_contractor_id;
        $data->store_keeper_id   = $request->store_keeper_id;
       
        $data->save();

        $response['status_code'] = '200';
        if ($request->has('site_info_id')) {
            $response['message'] = "Site info has been updated successfully";
        } else {
            $response['message'] = "Site info has been created successfully";
        }

        return $response;
    }
    public static function getAll(array $fields, array $filter = []): array
    {
        return self::select($fields)
        ->where($filter)
        ->get()
        ->toArray();
    }
    public static function getSiteInfoDetails($id)
    {
        $fields = [
            'site_info.id',
            'site_info.uuid',
            'site_info.id AS encryptid',
            'site_info.site_name',
            'site_info.site_location',
            'site_info.status as status_id',
            'se.name AS site_engineer_name',
            'sc.name AS sub_contractor_name',
            'sk.name AS store_keeper_name',
            DB::raw('CASE WHEN site_info.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(site_info.created_at, "%d-%b-%Y %r") AS date_created'),
        ];

        $query = self::select($fields)->leftjoin('staff_details as se','se.id','site_info.site_engineer_id')
        ->leftjoin('staff_details as sc','sc.id','site_info.sub_contractor_id')
        ->leftjoin('staff_details as sk','sk.id','site_info.store_keeper_id');

        $records = $query->where('site_info.uuid',$id)->get()->first();
		return $records;
    }

}
