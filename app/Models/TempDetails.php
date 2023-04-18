<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempDetails extends Model
{
    use HasFactory;

    protected $table = 'tempdetails';

    protected $guarded = [];

    public static function store(array $data): bool
    {
        $fieldsForSave = [
            'email',
            'otp',
            'user_id',
            'expired_at',
            'mobile_number',
            'step_data1',
            'step_data2',
        ];

        $model = new self();

        foreach ($data as $column => $value) {
            if (in_array($column, $fieldsForSave)) {
                $model->$column = $value;
            }
        }
        return $model->save();
    }

    public static function verifyOtp(array $data)
    {
        $filter['otp'] = $data['otp'];
        if (!empty($data['email'])) {
            $filter['email'] = $data['email'];
        }
        if (!empty($data['mobile_number'])) {
            $filter['mobile_number'] = $data['mobile_number'];
        }

        return self::where($filter)->whereRaw('? BETWEEN created_at AND expired_at', [$data['verified_at']])->first();
    }

    public static function fetchDetails(array $data): array
    {
        if (!empty($data['email'])) {
            $filter['email'] = $data['email'];
        }
        if (!empty($data['user_id'])) {
            $filter['user_id'] = $data['user_id'];
        }
        if (!empty($data['id'])) {
            $filter['id'] = $data['id'];
        }

        $model = self::where($filter)->first();
        if ($model) {
            return $model->toArray();
        } else {
            return $result = [];
        }
    }
}
