<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Industry;
use App\Models\Domains;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\DomainsUser;
use App\Models\subscription;
use App\Models\DomainForPurchase;
use App\Models\DynamicContent;
use App\ContentManagement;
use Auth;
use Session;
use Validator;
use Stripe;
use DB;
// use Session;
// use auth;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showCron($command, $param='')
    {
        if($param) {
            $artisan = \Artisan::call($command.":".$param);
        }
        else {
            $artisan = \Artisan::call($command);
        }
        $output = \Artisan::output();
        return $output;
    }

    public function index(Request $request, $id=null)
    {

        $data['membership_credit_cost'] = getGeneralSetting('subscription_cost');
        $data['year_credit_cost'] = getGeneralSetting('yearly_per_credit_cost');
        $data['month_credit_cost'] = getGeneralSetting('monthly_per_credit_cost');

        ### code to get current subscription
        $userid=Auth::id(); 
        $subscription = new subscription();     
        $subscriptionInfo = $subscription->getCurrentSubscription($userid); 
        $data['subscriptionInfo'] = $subscriptionInfo;
        $subscriptionInfoMember = $subscription->getCurrentMembershipSubscription($userid); 
        $data['subscriptionInfoMember'] = $subscriptionInfoMember;
        $data['subscriptionbtntext']  = $subscription->checkUserNewOrNot($userid); 

        $data['subscriptionMembership'] = ContentManagement::where('section','pricing_purchase_subscription')->first();
        $data['subscriptionMonthly'] = ContentManagement::where('section','pricing_purchase_monthly_credits')->first();
        $data['subscriptionYearly'] = ContentManagement::where('section','pricing_purchase_yearly_credits')->first();
        $data['subscriptionMonitor'] = ContentManagement::where('section','domain_monitoring')->first();
        $data['pricingPurchaseSubscription'] = ContentManagement::where('section','pricing_purchase_subscription')->first();
        $data['domain_id'] = $id;
        
        return view('front.subscriptionPlan')->with($data);
    }

    public function pricing(Request $request, $id=null)
    {   
        $userid = 0;
        $data['membership_credit_cost'] = getGeneralSetting('subscription_cost');
        $data['year_credit_cost'] = getGeneralSetting('yearly_per_credit_cost');
        $data['month_credit_cost'] = getGeneralSetting('monthly_per_credit_cost');

        ### code to get current subscription
        $subscription = new subscription();     
        $subscriptionInfo = $subscription->getCurrentSubscription($userid); 
        $data['subscriptionInfo'] = $subscriptionInfo;
        $subscriptionInfoMember = $subscription->getCurrentMembershipSubscription($userid); 
        $data['subscriptionInfoMember'] = $subscriptionInfoMember;
        $data['subscriptionbtntext']  = $subscription->checkUserNewOrNot($userid); 
        
        $data['subscriptionMembership'] = ContentManagement::where('section','pricing_purchase_subscription')->first();
        $data['subscriptionMonthly'] = ContentManagement::where('section','pricing_purchase_monthly_credits')->first();
        $data['subscriptionYearly'] = ContentManagement::where('section','pricing_purchase_yearly_credits')->first();
        $data['subscriptionMonitor'] = ContentManagement::where('section','domain_monitoring')->first();
        $data['pricingPurchaseSubscription'] = ContentManagement::where('section','pricing_purchase_subscription')->first();
        $data['domain_id'] = $id;

        $DynamicContent = new DynamicContent();
        $data['data']['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['data']['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        
        return view('front.pricing')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *subscription-credit-store
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    function getExpiryDate($ptype){
        $current_date = date('Y-m-d');
        $userid=auth::id();
        if($ptype == 'month'){
            $expiry_date = date("Y-m-d", strtotime("+1 month", strtotime($current_date)));
        }else if($ptype == 'year'){
            $expiry_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
        }else{
            $subscription = subscription::where('user_id',$userid)->where('subscription_type','Membership')->orderBy('expire_date','desc')->first();

            if($subscription['expire_date'] > $current_date){
                $current_date = $subscription['expire_date'];
            }
            $expiry_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
        }
        return $expiry_date;
    }


    public function subscriptionDomains(Request $request)
    {
        $userid=auth::id();
        $ptype = $request->ptype;
        $qty = $request->qty;

        $domainInfo = array();
        if($request->domain_id >0) {
            $domainInfo = Domains::where('ds_domains.id',$request->domain_id)
                ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
                ->where('ds_domain_users.user_id', $userid)
                ->first();
        }

        $current_date = date('Y-m-d');
        if($ptype == 'membership') {
            $qty = 1;
            $domainInfo = Domains::where('added_as', 'S')
                ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
                ->where('ds_domain_users.user_id', $userid)
                ->first();
        }

        $data['expiry_date'] = $this->getExpiryDate($ptype);

        $data['industry'] = Industry::where('status','Active')->get();
        $data['types'] = getTypes();
        $data['ptype'] = $ptype;
        $data['qty'] = $qty;
        $data['domain_name']    = isset($domainInfo['domain_name'])?$domainInfo['domain_name']:'';
        $data['industry_name']  = isset($domainInfo['industry'])?$domainInfo['industry']:'';
        $data['domain_type']    = isset($domainInfo['type'])?$domainInfo['type']:'';

        return view('front.subscription-domains', $data);
    }

    public function subscriptionDomainsStore(Request $request)
    {   
        // return $request->all();        
        $userid=auth::id();
        // $validatedData = $request->validate([
        $rules = [
            'domain_name.*'=>'required',
            'domain_type.*'=>'required',
            'industry_name.*'=>'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        $validator->after(function($validator) use($request) {
            if(!empty($request->domain_name[0])){
                foreach ($request->domain_name as $key => $value) {
                    if(!empty($value)){
                        if(!checkdnsrr($value,"MX")) {
                            $validator->errors()->add('invalid_domain.'.$key, 'Entered domain does not exists.');
                        }   
                    }
                    if(!empty($value)){
                        if(checkDomainStatus($value)=='Inactive') {
                            $validator->errors()->add('invalid_domain.'.$key, 'Entered domain is Inactive status.');
                        }   
                    }
                }
            }
        });
        
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ## code to delete items after insert in main table
        DomainForPurchase::where('user_id',$userid)->delete();

        $domain_name = $request->domain_name;
        $domain_type = $request->domain_type;
        $industry_name = $request->industry_name;
        foreach ($domain_name as $key => $value) {
            $data = new DomainForPurchase;
            $data->user_id = $userid;
            $domain = refineDomain(trim($value));
            $data->domain_name = $domain;
            $data->type = $domain_type[$key];
            $data->industry = $industry_name[$key];
            $data->save();
        }
        return redirect()->route('subscription-checkout',['ptype'=>$request->ptype,'qty'=>$request->qty])->with('successMsg',"Added Successfully.");
    }

    public function subscriptionCheckout(Request $request){

        $ptype = $request->ptype;
        $qty = $request->qty;
        
        if($ptype == 'membership') {
            $data['subscriptionType'] = 'Membership';
            $data['validity'] = '1 Year';
            $data['amount'] = getGeneralSetting('subscription_cost');
        }
        elseif($ptype == 'month'){
            $data['subscriptionType'] = 'Purchase Credits';
            $data['validity'] = '1 Month';
            $data['amount'] = getGeneralSetting('monthly_per_credit_cost');
        }
        else{
            $data['subscriptionType'] = 'Purchase Credits';
            $data['validity'] = '1 Year';
            $data['amount'] = getGeneralSetting('yearly_per_credit_cost');
        }

        $data['amount'] = number_format($qty*$data['amount'],2);
        
        $data['auto_payment'] = 'Yes';
        $data['ptype'] = $ptype;
        $data['qty'] = $qty;
        $data['checkpaymntuser'] = $request->checkpaymntuser;

        return view('front.subscription-checkout', $data);
    }

    public function stripePost(Request $request)
    {
        echo "LLLLLLLL12";
        $stripe_secret_key = getGeneralSetting('stripe_secret_key');

        $stripe = Stripe\Stripe::setApiKey($stripe_secret_key);
 
        try {
            $token = \Stripe\Token::create(array(
                "card" => array(
                    // "number"    => '4111111111111111',
                    "number"    => '4242424242424242',
                    "exp_month" => '11',
                    "exp_year"  => '2024',
                    "cvc"       => '123'
                )));
 
        } catch(\Stripe\Exception\CardException $e) {
          // Since it's a decline, \Stripe\Exception\CardException will be caught
          // echo 'Status is:' . $e->getHttpStatus() . '\n';
          // echo 'Type is:' . $e->getError()->type . '\n';
          // echo 'Code is:' . $e->getError()->code . '\n';
          // echo 'Param is:' . $e->getError()->param . '\n';
          echo 'Message is:' . $e->getError()->message . '\n';
        } catch (\Stripe\Exception\RateLimitException $e) {
            echo "KKK";
          // Too many requests made to the API too quickly
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            echo "KKK";
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Exception\AuthenticationException $e) {
            echo "KKK";
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            echo "KKK";
          // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo "KKK";
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
            echo "KKK";
          // Something else happened, completely unrelated to Stripe
        }

        if (!isset($token['id'])) {
            return redirect()->route('addmoney.paymentstripe');
        }

        ## code to save card for the customer
        $customer = \Stripe\Customer::create([
            'name' => 'Awdhesh Kumar',
            'email' => 'awdhesh@singsys.com',
            'card' => $token['id'],
            'description' => 'Test customer'
        ]);
        echo $stripe_id = $customer->id;

        // $cardholder = \Stripe\Issuing\Cardholder::create([
        //   'name' => 'Awdhesh Kumar',
        //   'email' => 'awdhesh@singsys.com',
        //   // 'phone_number' => '+18008675309',
        //   'status' => 'active',
        //   'type' => 'individual',
        //   // 'billing' => [
        //   //   'address' => [
        //   //     'line1' => '123 Main Street',
        //   //     'city' => 'San Francisco',
        //   //     'state' => 'CA',
        //   //     'postal_code' => '94111',
        //   //     'country' => 'US',
        //   //   ],
        //   // ],
        // ]);

        // $card = \Stripe\Issuing\Card::create([
        //   'cardholder' => 'ich_1Cm3pZIyNTgGDVfzI83rasFP',
        //   'type' => 'virtual',
        //   'currency' => 'usd',
        // ]);

        $charge = \Stripe\Charge::create([
            // 'card' => $token['id'],
            'currency' => 'SGD',
            'amount' =>  1 * 100,
            'description' => 'wallet',
            'customer' => $stripe_id,
        ]);


        if($charge['status'] == 'succeeded') {
            $transaction_id = $charge['id'];
            $txn_number = $charge['balance_transaction'];
            
            echo "success1";

            dd($charge->source);
        } else {
            echo "error";
        }
    }

    public function subscriptionPayment(Request $request)
    {   
        $user_id = auth::id();
        $email = Auth::user()->email;
        $name = Auth::user()->name;
        $stripe_id = Auth::user()->stripe_id;

        $validator = Validator::make($request->all(), [
            'card_number' => 'required|digits:16',
            'card_holder_name' => 'required|string|max:40',
            'card_expiry' => 'required',
            'cvv' => 'required|digits:3',
        ]);

        $validator->after(function ($validator) use ($request) {
            if($request->promo_code) {
                $errormessage = $this->checkPomoCode($request->promo_code);
                if($errormessage) {
                    $validator->errors()->add('promo_code', $errormessage);
                }
            }

            $expiry = explode("/", $request->card_expiry);
            if(count($expiry) == 2) {
                $exp_month = $expiry[0];
                $exp_year = $expiry[1];

                if($exp_year.$exp_month < date('Ym')){
                    $validator->errors()->add('card_expiry','The card expiry must be feature date.');
                }
            }
            else{
                $validator->errors()->add('card_expiry','Please enter valid card expiry.');
            }
        });

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->passes()) {

            $ptype = $request->ptype;
            $qty = $request->qty;

            $pathIfError = "subscription-checkout?ptype=".$ptype."&qty=".$qty;
            $added_as = 'C';
            if($ptype == 'month'){
                $subscription_plan = 'Monthly';
                $price = getGeneralSetting('monthly_per_credit_cost');
            }else if($ptype == 'year'){
                $subscription_plan = 'Yearly';
                $price = getGeneralSetting('yearly_per_credit_cost');
            }else{
                $added_as = 'S';
                $subscription_plan = 'Membership';
                $price = getGeneralSetting('subscription_cost');
            }

            $expiry = explode("/", $request->card_expiry);
            $exp_month = $expiry[0];
            $exp_year = $expiry[1];

            $stripe_secret_key = getGeneralSetting('stripe_secret_key');
            $stripe = Stripe\Stripe::setApiKey($stripe_secret_key);

            try {
                $token = \Stripe\Token::create(array(
                    "card" => array(
                        'number' => $request->card_number,
                        'exp_month' => $exp_month,
                        'exp_year' => $exp_year,
                        'cvc' => $request->cvv
                    )));
            }
            catch(\Stripe\Exception\CardException $e) {
                // Since it's a decline, \Stripe\Exception\CardException will be caught
                // echo 'Status is:' . $e->getHttpStatus() . '\n';
                // echo 'Type is:' . $e->getError()->type . '\n';
                // echo 'Code is:' . $e->getError()->code . '\n';
                // echo 'Param is:' . $e->getError()->param . '\n';
                // echo 'Message is:' . $e->getError()->message . '\n';
                $errorMessage = $e->getError()->message;
                return redirect($pathIfError)->with('error', $errorMessage);
            } catch (\Stripe\Exception\RateLimitException $e) {
                // Too many requests made to the API too quickly
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Exception\AuthenticationException $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                // Network communication with Stripe failed
            } catch (\Stripe\Exception\ApiErrorException $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
            }
            catch (Exception $e) {

            }

            // code to check the card
            if (!isset($token['id'])) {
                return redirect($pathIfError)->with('error', 'Something went to wrong. Try again later.');
            }
            else {
                ## code to create customer on stripe
                // if(!$stripe_id) {
                    $customer = \Stripe\Customer::create([
                        'name' => $name,
                        'email' => $email,
                        'card' => $token['id'],
                        'description' => 'Payment for the trust dom membership or credit(s)'
                    ]);

                    $stripe_id = $customer->id;

                    $userUpdate = User::find($user_id);
                    $userUpdate->stripe_id = $stripe_id;
                    $userUpdate->update();
                // }

                ## amount calculation
                $totalAmount = $price*$request->qty;
                $promo_code = $request->promo_code;
                $payableAmount = $totalAmount;
                $discountAmount = 0;
                if($promo_code) {
                    $discountAmount = $this->getPomoCodeDiscount($totalAmount, $promo_code);
                    $payableAmount = $totalAmount - $discountAmount;

                    ### code to update promo code quantity
                    PromoCode::where('promo_code', $promo_code)
                        ->update([
                            'available' => DB::raw('available - 1'),
                            'used' => DB::raw('used + 1'),
                        ]);
                }

                $charge = \Stripe\Charge::create([
                    'currency' => 'SGD',
                    'amount' =>  $payableAmount*100,
                    'description' => 'Purchase of membership or domain credit(s)',
                    'customer' => $stripe_id,
                ]);
     
                if($charge['status'] == 'succeeded') {
                    $expiry_date = $this->getExpiryDate($ptype);

                    $auto_payment = isset($request->auto_payment)?$request->auto_payment:'No';

                    $data = new subscription; 
                    $data->user_id = $user_id;
                    $data->subscription_type = $subscription_plan;
                    $data->promo_code = $promo_code;
                    $data->quantity = $request->qty;
                    $data->price = $price;
                    $data->total_amount = $totalAmount;
                    $data->discount = $discountAmount;
                    $data->paid_amount = $payableAmount;
                    $data->expire_date = $expiry_date;
                    $data->auto_payment = $auto_payment;
                    $data->transaction_number = $charge['balance_transaction'];
                    $data->transaction_id = $charge['id'];
                    $data->card_detail_id = $charge['payment_method'];
                    $data->transaction_status = 'Active';
                    $data->capture_return = '';
                    $data->save(); 
                    $subscription_id = $data->id;

                    $this->subscriptionDomainsUpdate($request, $subscription_id, $auto_payment);
                    return redirect('subscription-plan')->with('success', 'Payment has been completed sucessfully!');     
                } else {
                    return redirect($pathIfError)->with('error', 'Something went to wrong. Try again later.');
                }
            }
        }
    }

    ### check promo code  expiry
    public function validatePromoCodeAjax(Request $request) {
        $promo_code = $request->promo_code;
        $totalAmount = $request->totalAmount;

        $result['status'] = 'error';
        $result['promo_code'] = $promo_code;

        ## code to get data on basis of the promo code.
        $message = $this->checkPomoCode($promo_code);

        $discountAmount = 0;
        if ($message == '') {
            $result['status'] = 'success';
            $message = "Promo code is applied.";

            $discountAmount = $this->getPomoCodeDiscount($totalAmount, $promo_code);
            $payableAmount = $totalAmount - $discountAmount;
            $result['discountAmount']   = number_format($discountAmount, 2);
            $result['payableAmount']    = number_format($payableAmount, 2);
        }
        $result['message'] = $message;
        return ($result);
    }

    public function getPomoCodeDiscount($totalAmount=0, $promo_code='') {
        ## code to get data on basis of the promo code.
        $promoCodeInfo = PromoCode::where('promo_code', $promo_code)
                            ->where('status', 'Active')
                            ->where('is_deleted', '0')
                            ->first();
        $discountAmount = 0;
        if (isset($promoCodeInfo->promo_code)) {
            // if ($promoCodeInfo->promo_type == 'Fixed') {
            //     $discountAmount = $promoCodeInfo->promo_value;
            // } else {
            //     $discountAmount = round(($totalAmount * $promoCodeInfo->promo_value / 100), 2);
            // }            
            $discountAmount = round(($totalAmount * $promoCodeInfo->discount / 100), 2);
            if($discountAmount > $totalAmount){
                $discountAmount = $totalAmount;
            }
        }
        return $discountAmount;
    }

    public function checkPomoCode($promo_code='') {
        ## code to get data on basis of the promo code.
        $promoCodeInfo = PromoCode::where('promo_code', $promo_code)
                            ->where('status', 'Active')
                            ->where('is_deleted', '0')
                            ->first();

        $message = '';
        if (isset($promoCodeInfo->promo_code)) {
            if ($promoCodeInfo->available < 1) {
                $message = "Promo Code is not in stock.";
            } elseif ($promoCodeInfo->expire_date < date('Y-m-d')) {
                $message = "Promo Code has been expired.";
            }
        }
        else {
            $message = "Please enter valid promo code.";
        }
        return $message;
    }

    public function subscriptionDomainsUpdate($request, $subscription_id, $auto_payment)
    {   
        $user_id = auth::id();

        $ptype = $request->ptype;
        $qty = $request->qty;
        $added_as = 'C';
        if($ptype == 'membership'){
            $added_as = 'S';

            ## code to all added code as credit domain because can have only one subscription domain
            DomainsUser::where('user_id', $user_id)
                ->update([
                  'added_as'=> 'C'
                ]);
        }

        $expiry_date = $this->getExpiryDate($ptype);
        $domainPurchase = DomainForPurchase::where('user_id',$user_id)->get();

        $domainCount = 0;
        foreach ($domainPurchase as $key => $value) {
            ## check and add domain in the domain data
            $domainCount++;
            if($qty >= $domainCount) {
                $value['subscription_id'] = $subscription_id;
                $value['auto_payment'] = $auto_payment;
                $value['added_as'] = $added_as;
                $domainInfo = checkAndAddDomain($value['domain_name'], $value['industry']);
                $domainsUserInfo = checkAndAddDomainUser($domainInfo->id, $user_id, $ptype, $value);
            }
        }
        ## code to delete items after insert in main table
        DomainForPurchase::where('user_id',$user_id)->delete();
    }

    public function subscriptionSuccess(Request $request) {
        return view('front.subscription-success')->with('successMsg',"Payment has been completed sucessfully!");
    }
}