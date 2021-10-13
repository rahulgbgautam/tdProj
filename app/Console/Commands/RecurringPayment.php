<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\subscription;
use App\Models\Domains;
use App\Models\DomainsUser;
use App\Models\User;
use App\Models\GeneralSetting;
use Stripe;

class RecurringPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RecurringPayment';

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
        $current_date = date('Y-m-d');

        ### code to get all membership plans and renew all the plans
        $getRenewalSubscription = subscription::select('subscriptions.*', 'users.stripe_id', 'users.name', 'users.email')
            ->join('users','subscriptions.user_id','=','users.id')
            ->where('subscription_type', 'Membership')
            ->where('expire_date', '<=', $current_date)
            ->where('auto_payment','Yes')
            ->whereNotNull('stripe_id')
            ->get();

        foreach ($getRenewalSubscription as $key => $dataInfo) {
            $getCurrentSubscription = subscription::select('expire_date')
                ->where('user_id',$dataInfo->user_id)
                // ->where('expire_date', '>=', $current_date)
                ->where('subscription_type', 'Membership')
                ->orderBy('expire_date','DESC')
                ->first();

            if($getCurrentSubscription->expire_date > $current_date) {
                continue;
            }

            ### code to collect payment
            $subsInfo = $this->paymentAndCreateSubscription($dataInfo);

            ### code to send email to user for payment detection
            if($subsInfo) {
                $subscription_id = $subsInfo->id;

                ### code to update sbscription domain expiry date
                $subsDomain = DomainsUser::select('id')
                    ->where('user_id', $dataInfo->user_id)
                    ->where('added_as', '=', 'S')
                    ->orderBy('id', 'DESC')
                    ->first();

                $userUpdate = DomainsUser::find($subsDomain->id);
                $userUpdate->subscription_id = $subscription_id;
                $userUpdate->expiry_date = $subsInfo->expire_date;
                $userUpdate->update();

                ### code to send payment email to user
                $emailData = array();
                $emailData['name'] = ucwords($dataInfo->name);
                $emailData['email'] = $dataInfo->email;
                $emailData['promo_code']    = $subsInfo->promo_code;
                $emailData['expiry_date']   = showDate($subsInfo->expire_date);
                $emailData['total_amount']  = number_format($subsInfo->total_amount, 2);
                $emailData['discount']      = number_format($subsInfo->discount, 2);
                $emailData['paid_amount']   = number_format($subsInfo->paid_amount, 2);
                
                sendEmail(['email'=>$dataInfo->email,'name'=>$dataInfo->name],'subscriptionRenewal',$emailData);
            }
        }

        echo "Membership renewal process completed successfully.";

        ### code to get all membership plans and renew all the plans
        $getRenewalDomains = Domains::select('subscriptions.*','ds_domain_users.id', 'ds_domains.domain_name', 'users.stripe_id', 'users.name', 'users.email')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->join('subscriptions','ds_domain_users.subscription_id','=','subscriptions.id')
            ->join('users','subscriptions.user_id','=','users.id')
            ->where('ds_domains.status', 'Active')
            ->where('ds_domain_users.expiry_date', '<=', $current_date)
            ->where('ds_domain_users.auto_payment','Yes')
            ->whereNotNull('stripe_id')
            ->groupBy('ds_domains.id')
            ->get();

        foreach ($getRenewalDomains as $key => $dataInfo) {
            ### code to collect payment
            $subsInfo = $this->paymentAndCreateSubscription($dataInfo);

            ### code to send email to user for payment detection
            if($subsInfo) {
                $subscription_id = $subsInfo->id;

                ### code to update domain expire date and subscription id
                $userUpdate = DomainsUser::find($dataInfo->id);
                $userUpdate->subscription_id = $subscription_id;
                $userUpdate->expiry_date = $subsInfo->expire_date;
                $userUpdate->update();

                ### code to send payment email to user
                $emailData = array();
                $emailData['name'] = ucwords($dataInfo->name);
                $emailData['email'] = $dataInfo->email;
                $emailData['domain_name'] = $dataInfo->domain_name;
                $emailData['promo_code']    = $subsInfo->promo_code;
                $emailData['expiry_date']   = showDate($subsInfo->expire_date);
                $emailData['total_amount']  = number_format($subsInfo->total_amount, 2);
                $emailData['discount']      = number_format($subsInfo->discount, 2);
                $emailData['paid_amount']   = number_format($subsInfo->paid_amount, 2);

                sendEmail(['email'=>$dataInfo->email,'name'=>$dataInfo->name],'domainRenewal',$emailData); 
            }
        }

        echo "<br>";
        echo "Domain(s) renewal process completed successfully.";
    }

    public function paymentAndCreateSubscription($dataInfo){
        $current_date = date('Y-m-d');
        $quantity = 1;

        $subsInfo = '';

        if($dataInfo->subscription_type == 'Membership') {
            $current_price = getGeneralSetting('subscription_cost');
            $expire_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
        }
        elseif($dataInfo->subscription_type == 'Monthly'){
            $current_price = getGeneralSetting('monthly_per_credit_cost');
            $expire_date = date("Y-m-d", strtotime("+1 month", strtotime($current_date)));
        }
        else{
            $current_price = getGeneralSetting('yearly_per_credit_cost');
            $expire_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
        }

        /*
        if($dataInfo->price > $current_price || $dataInfo->paid_amount == 0) {
            $price = $current_price;
            $discount = 0;
            $promo_code = '';
        }
        else{
            $promo_code = $dataInfo->promo_code;
            $price = $dataInfo->price;
            $discountPerQuantity = $dataInfo->discount / $dataInfo->quantity;
            $discount = round($discountPerQuantity, 2);
        }
        */
        $price = $current_price;
        $discount = 0;
        $promo_code = '';

        $total_amount = $price * $quantity;
        $paid_amount = $total_amount - $discount;


        if($paid_amount >= .5) {
            $stripe_id = $dataInfo->stripe_id;

            $stripe_secret_key = getGeneralSetting('stripe_secret_key');
            $stripe = Stripe\Stripe::setApiKey($stripe_secret_key);

            $charge = \Stripe\Charge::create([
                    'currency' => 'SGD',
                    'amount' =>  $paid_amount*100,
                    'description' => 'Renewal of membership or domain credit',
                    'customer' => $stripe_id,
                ]);

            if($charge['status'] == 'succeeded') {
                $transaction_number = $charge['balance_transaction'];
                $transaction_id = $charge['id'];
                $card_detail_id = $charge['payment_method'];
            }
            else{
                return $subsInfo;
            }
        }
        else {
            $transaction_number = 'free-renewal'.'-'.date('YmdHis').'-'.rand(1000,9999);
            $transaction_id = $transaction_number;
            $card_detail_id = 0;
        }

        $subsInfo = new subscription;
        $subsInfo->user_id = $dataInfo->user_id;
        $subsInfo->subscription_type = $dataInfo->subscription_type;
        $subsInfo->promo_code = $promo_code;
        $subsInfo->quantity = $quantity;
        $subsInfo->price = $price;
        $subsInfo->total_amount = $total_amount;
        $subsInfo->discount = $discount;
        $subsInfo->paid_amount = $paid_amount;
        $subsInfo->expire_date = $expire_date;
        $subsInfo->auto_payment = $dataInfo->auto_payment;
        $subsInfo->transaction_number = $transaction_number;
        $subsInfo->transaction_id = $transaction_id;
        $subsInfo->card_detail_id = $card_detail_id;
        $subsInfo->transaction_status = 'Active';
        $subsInfo->capture_return = $dataInfo->capture_return;
        $subsInfo->email_notify = $dataInfo->email_notify;
        $subsInfo->created_by = $dataInfo->created_by;
        $subsInfo->save();

        return $subsInfo;
    }
}
