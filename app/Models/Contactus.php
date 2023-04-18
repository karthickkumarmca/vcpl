<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Support\Facades\Crypt;

class Contactus extends Model
{
	use HasFactory, SoftDeletes;
	protected $table = 'contact_us';

	public $timestamps = true;

	protected $dates = [
		'deleted_at',
	];
}
