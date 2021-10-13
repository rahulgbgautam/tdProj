<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\subscription;

class SubscriptionPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SubscriptionPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $getCurrentSubscription = subscription::select('subscriptions.id as subscriptions_id','users.name','users.email')->join('users','users.id','=','subscriptions.user_id')
                                ->where('subscriptions.expire_date', '>=', date("Y-m-d", strtotime("+1 month", strtotime(date('Y-m-d')))))
                                ->where('subscriptions.subscription_type', 'Membership')
                                ->where('subscriptions.email_notify', '!=' ,'yes')
                                ->orderBy('subscriptions.expire_date','DESC')
                                ->get();
        foreach ($getCurrentSubscription as $key => $value) {
            $emailData['name']    = $value['name'];
            $emailData['title']   = 'Trust-dom';
            $emailData['email']   = $value['email'];
            sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'subscriptionrenewal',$emailData);
            subscription::where(['id'=>$value['subscriptions_id']])->update(['email_notify'=>'yes']);
        }
    }
}
