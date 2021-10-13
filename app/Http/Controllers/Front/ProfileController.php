<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use auth;
use App\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        //
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
    public function edit(Request $request)
    {
        $userid=auth::id();
        $user = User::find($userid);
        return view('front.profileEdit',compact('user'));
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
       $validatedData = $request->validate([
            'email'=>'required|email',
            'name'=>'required|max:20',
            'profile_image'=>'image|mimes:jpg,png,jpeg,svg'
        ]);
       if($request->file('profile_image')!=''){

            $img_path=uploadImage($request->file('profile_image'));
            unlinkImage($request->old_profile_image);
            
        }else{

                $img_path=$request->old_profile_image;
        }

        $user = User::find($id);
        if($user->email == $request->email){
            $user->name = $request->name;
            $user->profile_image = $img_path;
            $user->email = $request->email;
            $user->update();
            return redirect('/view-profile')->with('successMsg',"Profile Updated Successfully.");;
        }else{
            $data=User::where(['email'=>$request->email])->first();
            if($data){
                return redirect()->back()->with('emailError',"Email Already Used Please Enter New Email");
            }else{
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->profile_image = $request->profile_image;
                $user->update();
                return redirect('/view-profile')->with('successMsg',"Profile Updated Successfully.");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request){ 
        $userid=auth::id();
        $user = User::find($userid);
        return view('front.profileChangePassword',compact('user'));
    }

    public function profileChangePassword(Request $request,$id){
        
        $validatedData = $request->validate([
            'old_password'=>'required|min:8|max:15',
            'new_password'=>'required|min:8|max:15',
            'new_confirm_password'=>'required|min:8|max:15|same:new_password'
        ]);

        if($request->new_password === $request->new_confirm_password){

            $data = User::find($id);
            $password=$data->password;
            if(Hash::check($request->old_password,$password)){
               $data->password=Hash::make($request->new_password);
                $data->update();
                return redirect('/view-profile')->with('successMsg',"Password Changed Successfully.");
            }
            else{
                return back()->with('errorMsg',"You Have Enter Wrong Old Password");
            }         
        }
    }

}
