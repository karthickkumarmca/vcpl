<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Cement',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Centering Materials',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Lorry Materials',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Outturn',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Shop Materials',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Tools and Plants',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'category_name' => 'Tools and Plants B',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
