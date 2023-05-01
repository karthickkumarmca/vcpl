<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Clients extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

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
    public static function getclients($page, $offset, $sort, $search_filter)
    {
        $fields = [
            'clients.id',
            'clients.uuid',
            'clients.id AS encryptid',
            'clients.client_name',
            'clients.status as status_id',
            DB::raw('CASE WHEN clients.status = 1 THEN "Active" ELSE "In-Active" END AS status, DATE_FORMAT(clients.created_at, "%d-%b-%Y %r") AS date_created'),
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
            'clients' => $records,
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

        if ($request->has('clients_id')) {
            $clients = self::where(['uuid' => $request->clients_id])->first();
            $clients->updated_by = Auth::id();
            if(isset($request->created_at))
            {
                $clients->updated_at = $request->created_at; 
            }
        } else {
            $clients = new self();
            $clients->created_by = Auth::id();
            $clients->created_at = date('Y-m-d H:i:s');
            $clients->uuid = \Str::uuid()->toString();
            $clients->status = 1;
        }
        $clients->client_name = $request->client_name;
        $clients->save();

        $response['status_code'] = '200';
        $response['clients_id'] = $clients->id;
        if ($request->has('units_id')) {
            $response['message'] = "Roles has been updated successfully";
        } else {
            $response['message'] = "Roles has been created successfully";
        }
        $response['data'] = [
            'redirect_url' => url(route('clients-list')),
        ];

        return $response;
    }
}
