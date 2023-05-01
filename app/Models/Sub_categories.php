<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Sub_categories extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subcategories';

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
    public static function getsubcategories($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'subcategories.id',
            'subcategories.uuid',
            'subcategories.id AS encryptid',
            'subcategories.sub_category_name',
            'subcategories.status as status_id',
            'categories.category_name',
            DB::raw('CASE WHEN subcategories.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(subcategories.created_at, "%d-%b-%Y %r") AS date_created'),
        ];
        $query = self::select($fields)->leftjoin('categories','categories.id','subcategories.category_id');

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

        if ($request->has('sub_categories_id')) {
            $subcategories = self::where(['uuid' => $request->sub_categories_id])->first();
            $subcategories->updated_by = Auth::id();
            if(isset($request->created_at)) {
                $subcategories->updated_at = $request->created_at; 
            }
        } else {
            $subcategories = new self();
            $subcategories->created_by = Auth::id();
            $subcategories->created_at = date('Y-m-d H:i:s');
            $subcategories->uuid = \Str::uuid()->toString();
            $subcategories->status = 1;
        }
       
        $subcategories->sub_category_name = $request->sub_category_name;
        $subcategories->category_id = $request->category_id;
       
        $subcategories->save();

        $response['status_code'] = '200';
        $response['subcategories_id'] = $subcategories->id;
        if ($request->has('subcategories_id')) {
            $response['message'] = "subcategories has been updated successfully";
        } else {
            $response['message'] = "subcategories has been created successfully";
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
    
    public static function getsubcategoriesCount(){
        $fields = [
            DB::raw('Count(id) AS subcategories_count'),
        ];
        $result = self::select($fields)
        ->get()
        ->toArray();
        return isset($result[0]['subcategories_count'])?$result[0]['subcategories_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('subcategories')->where('id', $id)->update($data);
        return $id;
    }
    public static function updateDetails($where,$updateDetails)
    {
        self::where($where)->update($updateDetails);
        return true;
    }
   
}
