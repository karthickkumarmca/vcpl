<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StafgroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('staffgroups')->insert([
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'group_name' => 'Office Staffs',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'group_name' => 'Security Officers',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'group_name' => 'Site Staffs',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'group_name' => 'StoreKeepers',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'group_name' => 'Subcontractors',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
