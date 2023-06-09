<?php

namespace App\Models\appview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
class Labour_transactions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'labour_movement_list';
    public static function storeRecords($request)
    {
        $site_id = Session::get('site_id');        
        $numberOfLabour            = !empty($request->number_of_labour)?$request->number_of_labour:0;
        $labourDate                = !empty($request->labour_date)?$request->labour_date:'';
        if(count($numberOfLabour)>0) {
        	$strUuid = \Str::uuid()->toString();
        	foreach($numberOfLabour as $k=>$l){
		        $data                      = new self();
		        $data->created_by          = Auth::id();
		        $data->created_at          = date('Y-m-d H:i:s');
		        $data->uuid                = $strUuid;
		        $data->subcontractor_id    = $request->subcontractor_id;
		        $data->labour_date         = $labourDate;
                $data->labour_category_id  = $k;
		        $data->number_of_labour    = !empty($l)?$l:0;
		        $data->shift_id            = $request->shift_id;
		        $data->save();
	        }
	        return 1;
        }
        return 0;
    }
}