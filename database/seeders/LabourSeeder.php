<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('labour_category')->insert([
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Barbender',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Carpenter',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Earthwork Men Mazdoor',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Earthwork Women Mazdoor',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Helper',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Mason',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Men Mazdoor',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Site Maistry',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Stone Cutter',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Women Mazdoor',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             
       ] );
    }
}
