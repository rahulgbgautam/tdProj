<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Hash;
use App\Models\DynamicContent;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    protected function showLoginForm() 
    {
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('auth.login')->with(['data'=>$data]);
    }
    protected function validateLogin(Request $request)
    {
        $recaptcha = getGeneralSetting('google_recapcha');
        if($recaptcha == 'yes') {
            $validationArr = [
                $this->username() => 'required|string|email',
                'password' => 'required|string',
                'g-recaptcha-response' => 'required|recaptcha'
            ];
        }
        else{
            $validationArr = [
                $this->username() => 'required|string|email',
                'password' => 'required|string'
            ];
        }

        $messageArr = [
            'g-recaptcha-response.recaptcha' => 'Captcha verification failed',
            'g-recaptcha-response.required' => 'Please complete the captcha'
        ];

        $request->validate($validationArr, $messageArr);
        
    }
    protected function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $date = Carbon::now('Asia/Kolkata');
            $formatedDate = $date->format('Y-m-d H:i:s');
            User::where(['email'=>$request->email])->update(['last_login_at'=>$formatedDate]);
            return $this->sendLoginResponse($request);
        }
        $userdata = User::where('email',$request['email'])->first();
        if(!$userdata){
            $msg = 'User does not exist';
            return redirect('login')->with('error', $msg);
        }
        $checkpass = true;
        if($userdata){
            $checkpass = Hash::check($request->password, $userdata->password);
        }
        // $checkpass = User::where(['email'=>$request['email'],'password'=>Hash::check($request->password)])->first();
        if($userdata['type']=='admin' || $userdata['type']=='super_admin' ){
            $msg = 'This user type is '.str_replace('_', ' ', $userdata['type']).', so can not loggedIn.';
            return redirect('login')->with('error', $msg);
        }else if($userdata['status']=='Inactive'){
            $msg = 'Your account has been inactive, please contact to your admin.';
            return redirect('login')->with('error', $msg);
        }else if(!$checkpass){
            $msg = 'Your email or password is wrong';
            return redirect('login')->with('error', $msg);
        }else if($userdata['user_verified']=='0'){
            $msg = 'Your email is not verified. Please check your email and click on the activation link';
            return redirect('login')->with('error', $msg);
        }else if($userdata['user_verified']=='1'){
            $msg = 'Your account is not yet active. <a href="'. route('activation-link',$request['email']) . '"> click here  </a> to resend the activation email.';
            return redirect('login')->with('success', $msg);
        }else{
            $msg = 'User does not exist';
            return redirect('login')->with('error', $msg);
        }
        
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
