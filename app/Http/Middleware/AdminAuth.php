<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($request->session()->has('admin')){

            $id = session('admin')['id'];
            $admin = User::find($id);
            if($admin){

                if($admin->status=="Active"){

                  }else{
                    session()->forget('admin');
                    return redirect('/admin');
                  }

              }else{
                session()->forget('admin');
                return redirect('/admin');
              }

        }elseif($request->session()->has('super_admin')){

            $id = session('super_admin')['id'];
            $admin = User::find($id);
            if($admin){

                if($admin->status=="Active"){

                  }else{
                    session()->forget('super_admin');
                    return redirect('/admin');
                  }

              }else{
                session()->forget('super_admin');
                return redirect('/admin');
              }
              
        }else{

            $request->session()->flash('Access_Denied',"You Are Not Authentic To Acces This Page");
            return redirect('admin');
        }

        return $next($request);
    }
}

