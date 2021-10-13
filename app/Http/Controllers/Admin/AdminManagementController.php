<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use App\Models\AssignRole;



class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $admin_type = session('admin')['type'];
        $id = session('admin')['id'];     
        $menu_write = menuPermissionByType($id,"write");
        if(in_array("admin-users",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        if($admin_type == "super_admin"){
            $AdminData = User::where('type','Admin')
                            ->where('user_verified','1')
                            ->latest('id')->paginate(10);
            return view('admin.adminManagement.adminList',compact('AdminData'));
        }else{
            $admin_id = session('admin')['id'];
            $AdminData = User::where('type','Admin')
                                ->where('id', '!=',$admin_id)
                                ->where('user_verified','1')
                                ->latest('id')->paginate(10);
            return view('admin.adminManagement.adminList',compact('AdminData','action_display'));
        }     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.adminManagement.adminAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validatedData = $request->validate([
                'name'=>'required|max:200',
                'email'=>'required|email|unique:users',
                'confirm_password'=>'required|same:password',
                'password'=>'required|min:8'
        ]);

        // return $request->_token;
        $emailData['name']  = $request->name;
        $emailData['email'] = $request->email;
        $emailData['password'] = $request->password;
        $emailData['title']   = 'Trust-dom';
        if($request->password === $request->confirm_password){
            $data = new User;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->user_verified = '1';
            $data->type = "admin";
            $data->email_verified_at = now();
            $data->remember_token = Str::random(10);
            $data->password = Hash::make($request->password);
            $data->save();
            sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name'],'password'=>$emailData['password']],'adminWelcomeMail',$emailData);           
            return redirect('admin/admin-management/createPermission/'.$data->id)->with('successMsg',"Admin Added Successfully.Now Add The Permissions For Added Admin.");

        }else{

            return redirect()->route('admin-management.create')->with('errorMsg',"Something went wrong.");
        }

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

        $admin=User::find($id);
        return view('admin.adminManagement.adminEdit',compact('admin'));
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
                'name'=>'required|max:50',
                'email'=>'required|email',
                'status'=>'required'
        ]);
        $admin = User::find($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = $request->status;
        $admin->update();
        return redirect()->route('admin-management.index')->with('successMsg',"Admin Updated Successfully.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
      
        $admin=User::find($id);
        $admin->delete();
        return redirect()->route('admin-management.index')->with('successMsg',"Admin Record Deleted Successfully.");
    }

    public function createPermission($id)
    {   
        $menuArray = menuname();
        $menu_read = menuPermissionByType($id,"read");
        $menu_write = menuPermissionByType($id,"write");
       return view('admin.adminManagement.adminPermissions',compact('menuArray','id','menu_read','menu_write'));
    }

    public function permissionsStore(Request $request,$id)
    {      
        // $validatedData = $request->validate([
        //         'menu_key'=>'required',
        //         'read'=>'required',
        //         'write'=>'required'
        // ]);
        $admin_id = $id;
        $data = AssignRole::where('user_id',$admin_id)->get();
        if($data){
            foreach ($data as $value) {
                $data = AssignRole::find($value->id);
                $data->delete();
            }
        }
        $read_values = $request->read;
        $write_values = $request->write;
        if($write_values){
            foreach($write_values as $write){
                $data = new AssignRole;
                $data->user_id = $admin_id;
                $data->menu_key = $write;
                $data->read = "1";
                $data->write = "1";
                $data->save();
            }
        }
        
        if($read_values){
            if($write_values){
                foreach($read_values as $read){
                    if(!in_array($read,$write_values)){
                        $data = new AssignRole;
                        $data->user_id = $admin_id;
                        $data->menu_key = $read;
                        $data->read = "1";
                        $data->write = "0";
                        $data->save();
                    }    
                }
            }else{
                foreach($read_values as $read){
                        $data = new AssignRole;
                        $data->user_id = $admin_id;
                        $data->menu_key = $read;
                        $data->read = "1";
                        $data->write = "0";
                        $data->save();    
                }
            }
            
        }
        return redirect()->route('admin-management.index')->with('successMsg',"Admin Permissions Added Successfully.");
         
    }

}

