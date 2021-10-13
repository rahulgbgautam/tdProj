<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use App\Models\Domains;
use App\Models\DomainScoreCategory;
use App\Models\DomainScanScore;
use App\Banner;
use App\Features;
use App\Models\DynamicContent;
use App\Models\GeneralSetting;
use App\Models\NewsLetter;
use App\ContentManagement;
use App\Faq;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Lib\UserVerification;
use Illuminate\Support\Facades\DB;
use Helpher;
use Session;
use App\Models\DomainForPurchase;
use App\Models\subscription;
use App\Models\DomainsUser;
use App\Models\ProbsCategory;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount(Request $request) 
  {
      if(!empty($user = User::where(['remember_token'=>$request->token])->first()))
      {
          User::where('id',$user->id)->update(['remember_token'=>NULL,'email_verified_at'=>date('Y-m-d h:i:s'),'user_verified'=>'1']);
         // $result['name'] = str_replace('$$', ' ',$user->name);
          // $result['name'] = $user->name;
          // $result['email'] = $user->email;
          // $mailContent['mailData'] = $result;
          // Mail::send('emails.users-already', $mailContent, function($message) use ($user)
          // {
          //     $message->to($user['email'], $user['name'])->subject('Welcome to ElephantApp');
          // });
          $user = User::where('id',$user->id)->first();
          $emailData['name']    = $user['name'];
          $emailData['title']   = 'Trust-dom';
          $emailData['email']   = $user['email'];
          sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'welcomemail',$emailData);
          return view('front/thankyou');
      }else{
          return view('front/already');
      }
  }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productpages(Request $request,$id)
    {
        $DynamicContent = new DynamicContent();
        $data['product_data'] = $DynamicContent->getDynamicContentById($id);
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');

         if($data['product_data']){
          return view('front.product')->with(['data'=>$data]);
        }else{
          return redirect('/');
        }
    }
    public function resourcepages(Request $request,$id)
    {        
        $DynamicContent = new DynamicContent();
        $data['resource_data'] = $DynamicContent->getDynamicContentById($id);
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        if($data['resource_data']){
          return view('front.resource')->with(['data'=>$data]);
        }else{
          return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     *cyber
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        $data['faq'] = Faq::orderBy('created_at','DESC')->where('status','active')->get();
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('front.faq')->with(['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function features()
    {
        $data['features'] = Features::where('status','active')->get();
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('front.features')->with(['data'=>$data]);
    }

    public function viewProfile(Request $request)
    {
      $userId = Auth::id();
      $user_data = User::select(DB::raw('DATE_FORMAT(ds_domain_users.expiry_date, "%d %b %Y") as date'),'users.name','users.email','users.profile_image')
                        ->join('ds_domain_users','ds_domain_users.user_id','=','users.id')
                        ->where(['users.id'=>$userId])
                        ->first();
                        // dd($user_data)  

        $transaction_data = subscription::where('user_id',$userId)
                                        ->where('transaction_status','Active')
                                        ->orderBy('expire_date','ASC')  
                                        ->get(); 

        // return $transaction_data;
                              
      return view('front.profileListing')->with(['data'=>$user_data,'transaction_data'=>$transaction_data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
   public function viewHome(Request $request)
    {
      // $userId = Auth::id();

      $data['main_banner'] = Banner::where('banner_type','main_banner')->where('status','active')->get();
      // $data['sec_banner'] = Banner::where('banner_type','sec_banner')->where('status','active')->get();
      $data['inside_banner'] = Banner::where('banner_type','inside_banner')->where('status','active')->get();
      $data['features'] = Features::where('status','active')->get();
      $data['subscription_purchase'] = ContentManagement::where('section','subscription_purchase')->first();
      $data['inside_dashboard'] = ContentManagement::where('section','inside_dashboard')->first();
      $data['faq_content'] = ContentManagement::where('section','faq')->first();
      $data['faq'] = Faq::where('status','active')->orderBy('created_at','DESC')->get();
      
      $DynamicContent = new DynamicContent();
      $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
      $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
      $data['industry_name'] = getIndustriesNew();
      $categoryList = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->orderBy('id','ASC')
                        ->where('status','Active')
                        ->get();
           Session::put('domain_name',null);
           Session::put('industry_id',null);
           // dd(Session::get('domain_name')); 

      // $activeCatCount = ProbsCategory::where('ds_probs_category.status', 'Active')
      //       ->count();
      // $data['activeCatCount'] = $activeCatCount;            
      $data['activeCatCount'] = 5;            

      return view('front.home')->with(['data'=>$data,'categoryList'=>$categoryList]);
    }
    public function about()
    {
        $data['about'] = ContentManagement::where('section','About_us')->first();
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('front.about')->with(['data'=>$data]);
    }
    public function term()
    {
        $data['term'] = ContentManagement::where('section','terms_and_condition')->first();
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('front.term')->with(['data'=>$data]);
    }
    public function privacy()
    {
        $data['privacy'] = ContentManagement::where('section','privacy_policy')->first();
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('front.privacy')->with(['data'=>$data]);
    }
    public function cookies()
    {
        $data['Cookies'] = ContentManagement::where('section','Cookies notification')->first();
        
        $DynamicContent = new DynamicContent();
        $data['product_dropdown'] = $DynamicContent->getDynamicContentByMenu('products');
        $data['resource_dropdown'] = $DynamicContent->getDynamicContentByMenu('resources');
        return view('front.cookies')->with(['data'=>$data]);
    }
    public function activationLink(Request $request,$email)
    {
      $user = User::where('email',$email)->first();
      UserVerification::generate($user);
      $verification_url = url('verify-account?token='.$user->remember_token);
      // $success['name'] =  str_replace('$$', ' ',$request->name);
      $emailData['name']    = $user['name'];
      $emailData['title']   = 'Trust-dom';
      $emailData['email']   = $user['email'];
      $emailData['link'] = $verification_url;
      sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'userRegistrationMail',$emailData);
      return redirect('login')->with('successmessage', 'You need to click on the email activation link to activate your account');
    }
    public function newsletterSubscribeAjax(Request $request) {
          $email = $request->email;
          $result['success'] = false;
          if ((!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email))) {
          $result['message'] = '<span class="text-danger">Please enter valid email.</span>';
          } else {
          # code to store news letter into database
          $newsletterModel = new NewsLetter();
          $newsletterModel->inserOrUpdateNewsletter($email);
          sendEmailMailChimp($email);
          $result['success'] = true;
          $result['message'] = '<span class="text-success">Newsletter subscribed successfully.</span>';
          }
          $result['email'] = $email;
          $emailData['title']   = 'Trust-dom';
          $emailData['email']   = $email;
          sendEmail(['email'=>$emailData['email'],'name'=>''],'newslettermail',$emailData);
          return ($result);
}

}
