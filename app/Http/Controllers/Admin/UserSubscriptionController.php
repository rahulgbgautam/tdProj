<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\subscription;
use Illuminate\Http\Request;



class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        
        $transaction_type = $request->transaction_type;
        $search = trim($request->search);
        $query=subscription::select('subscriptions.*','users.name','users.email')
                            ->leftJoin('users','users.id','=','subscriptions.user_id'); 

        if($transaction_type){
            $query->where('subscription_type',$transaction_type);
        }
        if (str_contains($search, '@')) {
            $query->where('users.email','LIKE','%'.$search.'%');
        }else{
            $query->where('users.name','LIKE','%'.$search.'%');
        }
        $subscriptionData = $query->orderBy('expire_date','ASC')->paginate(10);                                 
        return view('admin.userSubscription.userSubscriptionList',compact('subscriptionData','search','transaction_type'));
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
     * @param  \App\Models\subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(subscription $subscription)
    {
        //
    }
}
