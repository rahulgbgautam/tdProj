<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\subscription;
use date;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $search = trim($request->search);
        $id = session('admin')['id'];     
        $menu_write = menuPermissionByType($id,"write");
        if(in_array("portal-users",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $query = User::select('users.*','subscriptions.user_id')
            ->leftJoin('subscriptions','users.id','=','subscriptions.user_id')
            ->where('type','user')
            // ->where('user_verified','1')
            ->groupBy('users.id')
            ->latest('id');

        if($search){
            $query->where('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%')
                ->where('type','user')
                ->where('user_verified','1')
                ->groupBy('users.id')
                ->latest('id');
        }
        $UserData = $query->paginate(10);
        // return $UserData;                             
        return view('admin.userManagement.userList',compact('UserData','search','action_display'));
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
        $userData=User::find($id);
        return view('admin.userDetail',compact('userData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::find($id);
        return view('admin.userManagement.userEdit',compact('user'));
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
                'name'=>'required|max:30',
                'email'=>'required|email',
                'status'=>'required'
        ]);
        $user=User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->update();
        return redirect()->route('user-management.index')->with('successMsg',"User Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=User::findorfail($id);
        $data->delete();
        return redirect()->route('user-management.index')->with('successMsg',"User Record Deleted Successfully.");
    }


    public function subscriptions($id)
    {
        $subscriptionData = subscription::select('subscriptions.*','users.name')
                        ->leftJoin('users','users.id','=','subscriptions.user_id')
                        ->where('user_id',$id)
                        ->paginate(10);           
        return view('admin.userSubscription.userSubscriptionList',compact('subscriptionData'));
    }

    public function provideFreeAccess($id)
    {   
        $curr_date =  date("Ymd");
        $data = new subscription;
        $data->user_id = $id;
        $data->subscription_type = "Membership";
        $data->quantity = '0';
        $data->price = '0';
        $data->total_amount = '0';
        $data->expire_date = getExpiryDate($id);
        $data->transaction_number = $curr_date.rand(10000,99999);;
        $data->transaction_id = $curr_date.rand(10000,99999);;
        $data->card_detail_id = '0';
        $data->created_by = 'Admin';
        $data->save();
        return redirect()->route('user-management.index')->with('successMsg',"Free Access Provided Successfully.");
    }

}
