<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Messageheader extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'message_header';

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
            'message_header.id',
            'message_header.uuid',
            'message_header.id AS encryptid',
            'message_header.name',
            'message_header.description',
            'message_header.status as status_id',
            DB::raw('CASE WHEN message_header.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(message_header.created_at, "%d-%b-%Y %r") AS date_created'),
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

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('message_header_id')) {
            $data = self::where(['uuid' => $request->message_header_id])->first();
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
       
        $data->description            = $request->description;
        $data->name          = $request->name;
       
        $data->save();

        $response['status_code'] = '200';
        // $response['subcategories_id'] = $subcategories->id;
        if ($request->has('message_header_id')) {
            $response['message'] = "Product has been updated successfully";
        } else {
            $response['message'] = "Product has been created successfully";
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
