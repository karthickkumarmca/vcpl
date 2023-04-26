<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Staffgroups extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'staffgroups';

    protected $guarded = [];

    public function getEncryptidAttribute($value)
    {
        return encrypt(env('APP_KEY') . $value);
    }
    public static function getstaffgroups($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'staffgroups.id',
            'staffgroups.uuid',
            'staffgroups.id AS encryptid',
            'staffgroups.group_name',
            'staffgroups.status as status_id',
            DB::raw('CASE WHEN staffgroups.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(staffgroups.created_at, "%d-%b-%Y %r") AS date_created'),
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
            'staffgroups' => $records,
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
        if (!config("roles.{$role}.staffgroups_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('staff_groups_id')) {
            $staffgroups = self::where(['uuid' => $request->staff_groups_id])->first();
            $staffgroups->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $staffgroups->updated_at = $request->created_at; 
            }
        } else {
            $staffgroups = new self();
            $staffgroups->created_by = Auth::id();
            $staffgroups->created_at = date('Y-m-d H:i:s');
            $staffgroups->uuid = \Str::uuid()->toString();
            $staffgroups->status = 1;
        }
        $staffgroups->group_name = $request->group_name;
        $staffgroups->save();

        $response['status_code'] = '200';
        $response['staffgroups_id'] = $staffgroups->id;
        if ($request->has('units_id')) {
            $response['message'] = "Staff groups has been updated successfully";
        } else {
            $response['message'] = "Staff groups has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('staffgroups-list')),
        ];

        return $response;
    }
}
