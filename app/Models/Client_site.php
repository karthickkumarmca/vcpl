<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Client_site extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_info';

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
            'client_info.id',
            'client_info.uuid',
            'client_info.id AS encryptid',
            'client_info.client_name',
            'client_info.cader',
            'client_info.mobile_number',
            'client_info.email_id',
            'client_info.address',
            'site_info.site_name',
            'client_info.status as status_id',
             DB::raw('CASE WHEN client_info.status = 1 THEN "Active" ELSE "In-Active" END AS status,
              DATE_FORMAT(client_info.created_at, "%d-%b-%Y %r") AS date_created'),
           
           
        ];
        $query = self::select($fields)->leftjoin('site_info','site_info.id','client_info.site_id');

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

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('client_name_id')) {
            $materials = self::where(['uuid' => $request->client_name_id])->first();
            $materials->updated_by = Auth::id();
            if(isset($request->created_at)) {
                $materials->updated_at = $request->created_at; 
            }
        } else {
            $materials = new self();
            $materials->created_by = Auth::id();
            $materials->created_at = date('Y-m-d H:i:s');
            $materials->uuid = \Str::uuid()->toString();
            $materials->status = 1;
        }
      

        $materials->client_name                = ucfirst($request->client_name);
        $materials->site_id                     = $request->site_id;
        $materials->cader                       = $request->cader;
        $materials->mobile_number               = $request->mobile_number;
        $materials->email_id                    = $request->email_id;
        $materials->address                     = $request->address;
       
        $materials->save();

        $response['status_code'] = '200';
        $response['subcategories_id'] = $materials->id;
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
