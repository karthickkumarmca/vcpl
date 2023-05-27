<?php

namespace App\Models\appview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labour_transactions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'labour_movement_list';
}
