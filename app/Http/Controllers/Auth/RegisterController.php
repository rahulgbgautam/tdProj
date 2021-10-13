<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Domains;
use App\Models\DomainsUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helphers\Helper;
use Illuminate\Support\Facades\Mail;
use App\Lib\UserVerification;
use Session;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DynamicContent;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // return Validator::make($data, [
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'max:15', 'confirmed'],
            'domain_name' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'industry' => 'required',
            'accept_term_condition' => 'required',
        ];
         $validator = Validator::make($data,$rules);
         $validator->after(function($validator) use($data) {
            if(!empty($data['domain_name'])){
         if(!checkdnsrr($data['domain_name'],"MX")) {
             $validator->errors()->add('domain_name', 'Entered domain does not exists');
      }   
        }
    });
         return $validator;
}
protected function showRegistrationForm() 
    {
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('auth.register')->with(['data'=>$data]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $result = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $data['added_as'] = 'S';
        $industry = isset($data['industry'])?$data['industry']:0;
        $domainInfo = checkAndAddDomain($data['domain_name'], $industry);
        $domainsUserInfo = checkAndAddDomainUser($domainInfo->id, $result->id, 'adminfield', $data);

        // $domain_data=Domains::where('domain_name',$data['domain_name'])->first();
        // if(!$domain_data){
        //     $domain_data = Domains::create([
        //     'domain_name' => $data['domain_name'],
        //     'last_scan_date' => '',
        //     'average_score' => '0'
        // ]);
        // }
        // $domain_exists=DomainsUser::where('domain_id',$domain_data['id'])->whereNull('user_id')->exists();
        // if($domain_exists){

        //     $domain = DomainsUser::where('domain_id',$domain_data['id'])->whereNull('user_id')->update([
        //     'industry' => $data['industry'],
        //     'user_id' => $result->id,
        //     'average_score' => '0',
        //     'added_as' => 'S',
        //     'type' => '1',
        // ]);
        // }
        // else{

        // $domain = DomainsUser::create([
        //     'domain_id' => $domain_data['id'],
        //     'industry' => $data['industry'],
        //     'user_id' => $result->id,
        //     'average_score' => '0',
        //     'added_as' => 'S',
        //     'type' => '1',
        // ]);
        // }

        // $emailData['name']    = $data['name'];
        // $emailData['title']   = 'Trust-dom';
        // $emailData['email']   = $data['email'];
        // $emailData['url']    = 'www.google.com';
        // Helper::send_email($data['email'],$data['name'],'welcome_email',$emailData);

        UserVerification::generate($result);
        $verification_url = url('verify-account?token='.$result->remember_token);
        // $success['name'] =  str_replace('$$', ' ',$request->name);
        $emailData['name']    = $data['name'];
        $emailData['title']   = 'Trust-dom';
        $emailData['email']   = $data['email'];
        $emailData['link'] = $verification_url;
        sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'userRegistrationMail',$emailData);
        Session::put('domain_name',null);
        Session::put('industry_id',null);
        return $result;
    }
}
