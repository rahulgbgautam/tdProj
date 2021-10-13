<?php

namespace App\Http\Middleware;
use Auth;
use App\Models\User;

use Closure;
use Illuminate\Http\Request;

class CheckUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = Auth::id();
        if($userId){
            // $user_data = User::where(['status'=>'inactive','id'=>$userId])->first();
            $user_data = User::where(['id'=>$userId])->first();
            if(!$user_data){
                $request->session()->flash('Access_Denied',"User does not exist");
                Auth::logout();
                return redirect('login')->with('successmessage', 'User does not exist');
            }
            if($user_data['status']=='Inactive'){
                $request->session()->flash('Access_Denied',"You Are Not Authentic To Acces This Page");
                Auth::logout();
                return redirect('login')->with('successmessage', 'Your account has been inactive, please contact to your admin.');
            }
        }else {
            return redirect('login');
        }
        $user_data = User::where(['id'=>$userId])->first();
        return $next($request);
    }
}
