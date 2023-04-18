<?php

namespace App\Console\Commands;

use DateTime;

use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangeStatusToExpired extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'location:change_status_expired';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Change the status to expired for every days between 06:00 AM';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		app('log')->info('Enter cron tab handle');
		$current_date = date('Y-m-d');
		$dateTime = \DateTime::createFromFormat('Y-m-d', $current_date);
		$a=$dateTime->format('Y-m-d');
		DB::table('campaigns')->where('campaign_expireddate','<',$a)->where('status',1)->update(['status' => 2]); 
	}
}
