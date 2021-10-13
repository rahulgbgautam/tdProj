<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Faq;
use App\User;
use App\Banner;
use App\Models\subscription;
use App\Models\Domains;
use Mail;
use Auth;



class AdminController extends Controller 
{

    public function index(Request $request){
        if($request->session()->has('admin')){
            return redirect('/admin/dashboard');
        }else{
            return view('admin.auth.login');
        }
    }

    public function dashboard(Request $request){ 

        $data = array();
        $adminUserCount = User::where('type','admin')
                                ->where('status','Active')
                                ->count(); 
        $data['adminUserCount'] = $adminUserCount;


        $userCount = User::where('type','user')
                            ->where('user_verified','1')
                            ->where('status','Active')
                            ->count();  
        $data['userCount'] = $userCount;


        $MembershipCount = subscription::where('subscription_type','Membership')
                                            ->count(); 
        $data['MembershipCount'] = $MembershipCount;

        $MonthlyCount = subscription::where('subscription_type','Monthly')
                                            ->count(); 
        $data['MonthlyCount'] = $MonthlyCount;

        $YearlyCount = subscription::where('subscription_type','Yearly')
                                            ->count(); 
        $data['YearlyCount'] = $YearlyCount;


        $domainCount = Domains::count(); 
        $data['domainCount'] = $domainCount;

        $totalTransactionAmount = subscription::sum('paid_amount'); 
        $data['totalTransactionAmount'] = $totalTransactionAmount;

        return view('admin.dashboard', $data);
    }

    public function login(Request $request){
        $validatedData = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
            // 'g-recaptcha'=>'required|recaptcha'
        ]);

        $admin = User::where(['email'=>$request->email])->first();
        if($admin)
        {  
            if(!Hash::check($request->password,$admin->password))
            {  
                return back()->with('errorMessage',"Please enter correct email and password.");
            }else{ 
                if($admin->type=="super_admin" || $admin->type=="admin"){
                    if(strtolower($admin->status)=="active"){
                        $request->session()->put('admin',$admin);
                        return redirect('admin/dashboard');
                    }else{
                        return back()->with('errorMessage',"Your account is blocked by admin. Please contact to admin.");
                    }
                }else{
                    return back()->with('errorMessage',"You are not having admin section access. Please contact to admin.");
                }
            }

        }else{      
            return back()->with('errorMessage',"Your account does not exist.");
        }

    }

    public function profile(){

        $id = session('admin')['id'];
        $admin = User::find($id);
        return view('admin.profile.profile',compact('admin'));

    }

    public function edit(Request $request,$id){

        $admin = User::find($id);
        return view('admin.profile.profileEdit',compact('admin'));
    }

    public function update(Request $request,$id){

        $validatedData = $request->validate([
            'profile_image'=>'image|mimes:jpg,png,jpeg,svg',
            'email'=>'required|email',
            'name'=>'required|max:20'
        ]);

         if($request->file('profile_image')!=''){

            $img_path = uploadImage($request->file('profile_image'));
            unlinkImage($request->old_profile_image);
            
        }else{

                $img_path = $request->old_profile_image;
        }

        $admin = User::find($id);
        if($admin->email == $request->email){
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->profile_image = $img_path;
            $admin->update();
            return redirect('/admin/profile')->with('successMsg',"Profile Updated Successfully.");;
        }else{
            $data=User::where(['email'=>$request->email])->first();
            if($data){
                return redirect()->back()->with('emailError',"Email Already Used Please Enter New Email");
            }else{
                $admin = User::find($id);
                $admin->name = $request->name;
                $admin->email = $request->email;
                $admin->profile_image = $img_path;
                $admin->update();
                return redirect('/admin/profile')->with('successMsg',"Profile Updated Successfully.");
            }
        }
    }

    public function profile_change_password($id){
        $admin = User::find($id);
        return view('admin.profile.profileChangePassword',compact('admin'));
    }

    public function profile_change_password_process(Request $request,$id){
        
        $validatedData = $request->validate([
            'old_password'=>'required|min:8',
            'new_password'=>'required|min:8',
            'new_confirm_password'=>'required|min:8|same:new_password'
        ]);

        if($request->new_password === $request->new_confirm_password){

            $data = User::find($id);
            $password=$data->password;
            if(Hash::check($request->old_password,$password)){
               $data->password=Hash::make($request->new_password);
                $data->update();
                return redirect('admin/profile')->with('successMsg',"Password Changed Successfully.");
            }
            else{
                return back()->with('errorMsg',"You Have Entered Wrong Old Password");
            }         
        }
    }

    public function forgotPassword(Request $request){
        $validatedData = $request->validate([
            'email'=>'required|email'
        ]);
        $result=DB::table('users')  
        ->where(['email'=>$request->email])
        ->get(); 
        if(isset($result[0])){
            if(!($result[0]->type == 'user')){
                $token=$result[0]->remember_token;
                $name=$result[0]->name;
                $email=$result[0]->email;
                $verification_url = url('admin/reset_password',$token);
                $emailData['name']  = $name;
                $emailData['email'] = $email;
                $emailData['token'] = $token;
                $emailData['link'] = $verification_url;
                sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'adminForgotMail',$emailData);
                $request->session()->flash('errorMessage',"Check Your Email For Reset Password");
                return redirect('/admin');
            }else{
                return redirect()->back()->with('errorMessage',"You Have No Access To Change Password From Here.");
            }

        }else{
            return redirect()->back()->with('errorMessage',"Email Not Found");
        }

    }

    public function reset_password($id){

        $result=DB::table('users')  
        ->where(['remember_token'=>$id])
        ->get();
        if(isset($result[0])){

            return view('admin.auth.resetPasswordForm',compact('id'));

        }else{
            
            $request->session()->flash('errorMessage',"Link Expired");
            return redirect('/admin');
        }

    }

    public function reset_password_process(Request $request,$id)
    {   

       $validatedData = $request->validate([

        'password'=>'required|min:8',
        'confirm_password'=>'required'

    ]);

       $result=DB::table('users')  
       ->where(['remember_token'=>$id])
       ->get(); 

       if(isset($result[0])){
        if ($request->password === $request->confirm_password) {
            DB::table('users')  
            ->where(['id'=>$result[0]->id])
            ->update(['password'=>Hash::make($request->password)]);
            $request->session()->flash('errorMessage',"Password Updated Successfully");                
            return redirect('/admin');

        }else{

            $request->session()->flash('errorMessage',"Please Enter Same Password");
            return redirect()->back();

        }

    }else{
        
        $request->session()->flash('errorMessage',"Please Select Right Link For Password Update");
        return redirect('/admin');
    }

}

}
