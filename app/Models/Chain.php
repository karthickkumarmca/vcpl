<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Chain extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chain';

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
    public static function getChains($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'chain.id',
            'chain.uuid',
            'chain.id AS encryptid',
            'chain.chain',
            'hallmark.rate AS hallmark_id',
            'chain.status as status_id',
            DB::raw('CASE WHEN chain.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(chain.created_at, "%d-%b-%Y %r") AS date_created'),
        ];

        $query = self::select($fields)->join('hallmark AS hallmark', 'hallmark.id', 'chain.hallmark_id');

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
            'chains' => $records,
            'current_page' => $page + 1,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
        ];
    }
    public static function getChainbyUuid($uuid)
    {
        $fields = [
            'chain.id',
            'chain.uuid',
            'chain.id AS encryptid',
            'chain.chain',
            'hallmark.rate AS hallmark_id',
            'chain.status as status_id',
            'chain.created_at as created_at',
            DB::raw('CASE WHEN chain.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(chain.created_at, "%d-%b-%Y %r") AS date_created'),
        ];

        return self::select($fields)->join('hallmark AS hallmark', 'hallmark.id', 'chain.hallmark_id')->where('chain.uuid',$uuid)->first();
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
        if (!config("roles.{$role}.chain_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('chain_id')) {
            $chain = self::where(['uuid' => $request->chain_id])->first();
            $chain->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $chain->updated_at = $request->created_at; 
            }
        } else {
            $chain = new self();
            $chain->created_by = Auth::id();
            $chain->created_at = date('Y-m-d H:i:s');
        }
        $chain->uuid = \Str::uuid()->toString();
        $chain->chain = $request->chain;
        $chain->hallmark_id = $request->hallmark_id;
        $chain->status = 1;
        $chain->save();

        $response['status_code'] = '200';
        $response['chain_id'] = $chain->id;
        if ($request->has('chain_id')) {
            $response['message'] = "Chain has been updated successfully";
        } else {
            $response['message'] = "Chain has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('chain-list')),
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
    
    public static function getchainCount(){
        $fields = [
            DB::raw('Count(id) AS chain_count'),
        ];
        $result = self::select($fields)
        ->get()
        ->toArray();
        return isset($result[0]['chain_count'])?$result[0]['chain_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('chain')->where('id', $id)->update($data);
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
            DB::raw('COUNT(id) AS chains_count')           
        ];
        $query = Self::select($fields);
        $records = $query->first()->toArray();
        return $records;
    }
}
