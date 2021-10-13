<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use App\Models\Domains;
use App\Models\DomainsUser;
use App\Models\subscription;
use App\Models\User;
use Illuminate\Console\Command;
// use auth;

class creditsExpiryNotification extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string 
	 */
	protected $signature = 'creditsExpiryNotification';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send credit expiry notification';

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

		$expiry_date1 = date("Y-m-d", strtotime("+5 day", strtotime(date('Y-m-d'))));
        $expiry_date2 = date("Y-m-d", strtotime("+3 day", strtotime(date('Y-m-d'))));

        $users = Domains::select('ds_domains.*','users.name','users.email','ds_domain_users.expiry_date')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->join('users','users.id','=','ds_domain_users.user_id')
            ->whereIn('ds_domain_users.expiry_date', [$expiry_date1, $expiry_date2])
            ->groupBy('ds_domains.id')
            ->get();

        foreach ($users as $key => $userInfo) {
            $emailData['name']    = $userInfo['name'];
            $emailData['email']   = $userInfo['email'];
            $emailData['domain_name']   = $userInfo['domain_name'];
            $emailData['expiry_date']   = showDate($userInfo['expiry_date']);
            sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'creditExpiryNotification',$emailData);
        }
	}
}
