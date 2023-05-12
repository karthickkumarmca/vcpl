<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Materials extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'materials';

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
            'materials.id',
            'materials.uuid',
            'materials.id AS encryptid',
            'materials.rate_unit',
            DB::raw('CASE WHEN materials.from_date IS NULL THEN "-" ELSE 
                DATE_FORMAT(materials.from_date, "%d-%b-%Y")
             END
                AS from_date'),

            DB::raw('CASE WHEN materials.to_date IS NULL THEN "-" ELSE 
                DATE_FORMAT(materials.to_date, "%d-%b-%Y")
             END
                AS to_date'),

            'materials.status as status_id',
            'product_details.product_name as category_name',
            'units.unit_name',
            DB::raw('CASE WHEN materials.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(materials.created_at, "%d-%b-%Y %r") AS date_created'),
        ];
        $query = self::select($fields)
        ->leftjoin('product_details','product_details.id','materials.property_material_id')
        ->leftjoin('units','units.id','materials.units_id');

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

        if ($request->has('centering_materials_id')) {
            $materials = self::where(['uuid' => $request->centering_materials_id])->first();
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
        if ($request->has('from_date')) {
             $materials->from_date               = date('Y-m-d',strtotime($request->from_date));
        }
        if ($request->has('to_date')) {
             $materials->to_date               = date('Y-m-d',strtotime($request->to_date));
        }

        $materials->rate_unit               = $request->rate_unit;
        $materials->property_material_id    = $request->category_id;
        $materials->units_id                = $request->units_id;
        $materials->material_id             = $request->material_id;
       
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
