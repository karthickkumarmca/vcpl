<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
	protected $toTruncate = ['users'];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	
    	$this->call(UserSeeder::class);
    }
}
