<?php

namespace App\Models\appview;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
class Materials_stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'materials_stock';

    protected $guarded = [];
    
}
