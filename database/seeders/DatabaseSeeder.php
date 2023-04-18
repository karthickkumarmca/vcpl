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
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    	
    	foreach ($this->toTruncate as $table) {
    		DB::table($table)->truncate();
    	}

    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // User::factory(10)->create();
    	$this->call(UserSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(HallmarkSeeder::class);
        $this->call(ChainSeeder::class);
    }
}
