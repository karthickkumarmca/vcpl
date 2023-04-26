<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Bags',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Cft',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Coil',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Count',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Each',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Kgs',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => 'Load',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => "No's",
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => "Rft",
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => "Sft",
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'unit_name' => "Unit",
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
