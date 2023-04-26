<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Property_name extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_name';

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
    public static function getproperty_name($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'property_name.id',
            'property_name.uuid',
            'property_name.id AS encryptid',
            'property_name.property_name',
            'property_name.status as status_id',
            'property_category.category_name',
            'ownership.ownership_name',
            DB::raw('CASE WHEN property_name.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(property_name.created_at, "%d-%b-%Y %r") AS date_created'),
        ];
        $query = self::select($fields)->leftjoin('property_category','property_category.id','property_name.property_category_id')
        ->leftjoin('ownership','ownership.id','property_name.ownership_id');

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
        if (!config("roles.{$role}.property_name_management")) {
            abort(403);
        }

        $response = [];
        $response['status_code'] = config('response_code.Bad_Request');

        if ($request->has('property_name_id')) {
            $property_name = self::where(['uuid' => $request->property_name_id])->first();
            $property_name->updated_by = Auth::id();
            if(isset($request->created_at)) {
                $property_name->updated_at = $request->created_at; 
            }
        } else {
            $property_name = new self();
            $property_name->created_by = Auth::id();
            $property_name->created_at = date('Y-m-d H:i:s');
            $property_name->uuid = \Str::uuid()->toString();
            $property_name->status = 1;
        }
       
        $property_name->property_name           = $request->property_name;
        $property_name->property_category_id    = $request->category_id;
        $property_name->ownership_id            = $request->ownership_id;
       
        $property_name->save();

        $response['status_code'] = '200';
        $response['subcategories_id'] = $property_name->id;
        if ($request->has('subcategories_id')) {
            $response['message'] = "property-name has been updated successfully";
        } else {
            $response['message'] = "property-name has been created successfully";
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
    public static function getdashboardcount()
    {

        $fields = [
            DB::raw('COUNT(*) AS total_count'),
            DB::raw('COUNT(id) AS subcategoriess_count')           
        ];
        $query = Self::select($fields);
        $records = $query->first()->toArray();
        return $records;
    }
}
