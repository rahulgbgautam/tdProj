<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use App\Models\Domains;
use App\Models\DomainsUser;
use App\Models\subscription;
use App\Models\User;
use Illuminate\Console\Command;
// use auth;

class subscriptionExpiryNotification extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string 
	 */
	protected $signature = 'subscriptionExpiryNotification';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send subcription expiry notification';

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

        $users = subscription::select('subscriptions.*','users.name','users.email')
            ->join('users','users.id','=','subscriptions.user_id')
            ->whereIn('subscription_type', ['Membership'])
            ->whereIn('expire_date', [$expiry_date1, $expiry_date2])
            ->groupBy('users.id')
            ->get();

		foreach ($users as $key => $userInfo) {
            $emailData['name']    = $userInfo['name'];
            $emailData['email']   = $userInfo['email'];
            $emailData['expiry_date']   = showDate($userInfo['expire_date']);

            sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'subscriptionExpiryNotification',$emailData);
        }
	}
}
