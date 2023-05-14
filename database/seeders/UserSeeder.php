<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Super admin',
            'phonenumber' => '9876543210',
            'email' => 'developer@example.com',
            'password' => bcrypt('Qwerty23*'),
            'user_name' =>'superadmin',
            'user_type' => 1,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Super admin',
            'phonenumber' => '9876543211',
            'email' => 'admin@yopmail.com',
            'password' => bcrypt('Vcpladmin@2023'),
            'user_type' => 1,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]]
        );
    }
}
