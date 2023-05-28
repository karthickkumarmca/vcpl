<?php

namespace App\Models\appview;

use DB;
use Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\appview\Materials_stock;

class Tools_transactions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tools_transactions';

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
    public static function get_materials($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'tools_transactions.id',
            'tools_transactions.uuid',
            'tools_transactions.id AS encryptid',
            'tools_transactions.quantity',
             DB::raw('CASE WHEN tools_transactions.type = 1 THEN "TRANSFER" WHEN tools_transactions.type = 2 THEN "RECEIVED" ELSE "ISSUED" END AS type,
                DATE_FORMAT(tools_transactions.created_at, "%d-%b-%Y") AS date_created'),
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
        $site_id = Session::get('site_id');

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        
        $data = new self();
        $data->created_by = Auth::id();
        $data->created_at = date('Y-m-d H:i:s');
        $data->uuid = \Str::uuid()->toString();
        $data->status = 1;

        $data->type                     = 1;
        $data->quantity                 = round($request->quantity,2);
        $data->vehicle_id               = $request->vehicle_id;
        $data->site_id                  = $request->site_id;

        $getdata = Materials_stock::where('materials_category_id',6)->where('site_id',$site_id)->get()->toArray();
        if(count($getdata) > 0){
            Materials_stock::where(['materials_category_id'=>6,'site_id'=>$site_id])
            ->update(['stock'=>DB::raw('stock-'.round($request->quantity,2))]);
        }
        $data->from_site_id                 = $request->site_id;
        $data->save();

        $response['status_code'] = '200';
        $response['id'] = $data->id;
        if ($request->has('subcategories_id')) {
            $response['message'] = "Data has been updated successfully";
        } else {
            $response['message'] = "Data has been created successfully";
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
    
   
    public static function updateDetails($where,$updateDetails)
    {
        self::where($where)->update($updateDetails);
        return true;
    }
   
}
