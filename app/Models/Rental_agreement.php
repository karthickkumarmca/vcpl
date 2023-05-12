<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Rental_agreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rental_agreement';

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
            'rental_agreement.id',
            'rental_agreement.uuid',
            'rental_agreement.id AS encryptid',
            'rental_agreement.tenant_name',
            'rental_agreement.status as status_id',
            DB::raw('CASE WHEN rental_agreement.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(rental_agreement.created_at, "%d-%b-%Y %r") AS date_created'),
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

        if ($request->has('rental_agreement_id')) {
            $data = self::where(['uuid' => $request->rental_agreement_id])->first();
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
       
        $data->property_id              = $request->property_id;
        $data->tenant_name              = $request->tenant_name;
        $data->rent_start_date          = date('Y-m-d',strtotime($request->rent_start_date));
        $data->rent_end_date            = date('Y-m-d',strtotime($request->rent_end_date));
        $data->rental_area              = $request->rental_area;
        $data->rental_amount            = $request->rental_amount;
        $data->maintainance_charge      = $request->maintainance_charge;
        $data->next_increment           = $request->next_increment;
        $data->aadhar_number            = $request->aadhar_number;
        $data->pan_number               = $request->pan_number;
        $data->gst_in                   = $request->gst_in;
        $data->contact_person_name      = $request->contact_person_name;

        $data->contact_person_mobile_number             = $request->contact_person_mobile_number;
        $data->alternative_contact_person_name          = $request->alternative_contact_person_name;
        $data->alternative_contact_person_mobile_number = $request->alternative_contact_person_mobile_number;

        $data->present_rental_rate      = $request->present_rental_rate;
        $data->advance_paid             = $request->advance_paid;
        $data->payment_mode             = $request->payment_mode;
       
        $data->save();

        $response['status_code'] = '200';
        // $response['subcategories_id'] = $subcategories->id;
        if ($request->has('rental_agreement_id')) {
            $response['message'] = "Rental Agreement has been updated successfully";
        } else {
            $response['message'] = "Rental Agreement has been created successfully";
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
