<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use DateTime;
use Date;

class subscription extends Model
{
    use HasFactory;

    public function getCurrentSubscription($user_id=''){
        $getCurrentSubscription = $this->where('user_id',$user_id)
        	->where('expire_date', '>=', date("Y-m-d"))
        	->orderBy('expire_date','DESC')
        	->first();

        session(['subscription' => '']);
        session(['trial_time' => '']);
        if($user_id) {
            if($getCurrentSubscription){ 
            	session(['subscription' => 'yes']);
            }
            else{
                $trial_days = getGeneralSetting('signup_access_(in_days)');
                $user_data = User::where('id', $user_id)->first();
                $reg_date = $user_data->created_at;

                $your_date = strtotime($reg_date);
                $now = time(); // or your date as well
                $datediff = $now - $your_date;
                $reg_days = round($datediff / (60 * 60 * 24));
            
                if($trial_days < $reg_days) {
                    session(['trial_time' => 'expired']);
                }

            }
        }
        return $getCurrentSubscription;
    }
    public function getCurrentMembershipSubscription($userid){
        $getCurrentSubscription = $this->where('user_id',$userid)
            ->where('expire_date', '>=', date("Y-m-d", strtotime("+2 month", strtotime(date('Y-m-d')))))
            ->orderBy('expire_date','DESC')
            ->first();

        session(['membersubscription' => '']);
        if($getCurrentSubscription){ 
            session(['membersubscription' => 'yes']);
        }
        return $getCurrentSubscription;
    }
    public function checkUserNewOrNot($userid){
        $getCurrentSubscription = $this->where('user_id',$userid)
            ->orderBy('expire_date','DESC')
            ->first();
        if($getCurrentSubscription){ 
            return 'Yes';
        }else{
            return 'No';
        }
    }
}
