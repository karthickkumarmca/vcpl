<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Labour_categories extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'labour_category';

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
    public static function getcategories($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'labour_category.id',
            'labour_category.uuid',
            'labour_category.id AS encryptid',
            'labour_category.category_name',
            'labour_category.status as status_id',
            DB::raw('CASE WHEN labour_category.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(labour_category.created_at, "%d-%b-%Y %r") AS date_created'),
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
            'categories' => $records,
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
        if (!config("roles.{$role}.categories_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('categories_id')) {
            $categories = self::where(['uuid' => $request->categories_id])->first();
            $categories->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $categories->updated_at = $request->created_at; 
            }
        } else {
            $categories = new self();
            $categories->created_by = Auth::id();
            $categories->created_at = date('Y-m-d H:i:s');
            $categories->uuid = \Str::uuid()->toString();
            $categories->status = 1;
        }
       
        $categories->category_name = $request->category_name;
       
        $categories->save();

        $response['status_code'] = '200';
        $response['categories_id'] = $categories->id;
        if ($request->has('categories_id')) {
            $response['message'] = "categories has been updated successfully";
        } else {
            $response['message'] = "categories has been created successfully";
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
    
    public static function getcategoriesCount(){
        $fields = [
            DB::raw('Count(id) AS categories_count'),
        ];
        $result = self::select($fields)
        ->get()
        ->toArray();
        return isset($result[0]['categories_count'])?$result[0]['categories_count']:0;
    }

    public static function updateRecord(array $data, $id = 0): int
    {
        DB::table('categories')->where('id', $id)->update($data);
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
            DB::raw('COUNT(id) AS categoriess_count')           
        ];
        $query = Self::select($fields);
        $records = $query->first()->toArray();
        return $records;
    }
}
