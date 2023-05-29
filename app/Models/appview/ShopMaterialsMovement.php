<?php

namespace App\Models\appview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class ShopMaterialsMovement extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'shop_materials_movement_list';

    public static function storeRecords($request)
    {
        $site_id = Session::get('site_id') ;   

        $data                           = new self();
        $data->created_by               = Auth::id();
        $data->created_at               = date('Y-m-d H:i:s');
        $data->uuid                     = \Str::uuid()->toString();
        $data->material_id    	        = $request->material_id;
        $data->site_id                  = $site_id;
        $data->supply_score             = $request->supply_score;
        $data->delivery_chellan_number  = $request->delivery_chellan_number;
        $data->quantity                 = $request->quantity;
        $data->unit                     = $request->unit;
        $data->save();
        
        return $data->id;
    }

}
