<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;


class UserOtp extends Model
{
    use HasFactory;

    protected $table = 'user_otp';
	protected $fillable = [
        'id', 'user_id', 'otp', 'created_at', 'updated_at'
    ];

    ### Send Otp Code-----------------//
    public function sendOtp($user_id){
    	//get otp from the otp table
        $mins = getGeneralSetting('otp_expiry_duration');
        $startTime = date("Y-m-d H:i:s", strtotime('-'.$mins.' minutes', strtotime(date('Y-m-d H:i:s'))));
        // dd($startTime);
        $otpData = $this->where('user_id', $user_id)
	        ->where('updated_at', '>=', $startTime)
	        ->first();
        
        if(isset($otpData)) {
            $userOTP = $otpData->otp;
        }
        else{
            $userOTP = rand(1000, 9999);
            // $userOTP = 1111;
        }

        # code to delete previous OTP from the database
        $this->where('user_id', $user_id)->delete();

        # code store OTP
        $data = array(
            'otp' => $userOTP,
            'user_id' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
            );
        $this->insert($data);

        # get user info
        $userModel = new User();
        $userInfo = $userModel->getUserInfo($user_id);

        // if($userInfo->mobile_number) {
        //     $patternFind[0] = '/{OTP}/';
        //     $patternFind[1] = '/{MINS}/';
            
        //     $replaceFind[0] = $userOTP;
        //     $replaceFind[1] = $mins;
            
        //     $messageText = "VinoPatron OTP {OTP} is valid for {MINS} Mins.";
        //     $messageText = preg_replace($patternFind, $replaceFind, $messageText);

        //     sendMessage($messageText, $userInfo->mobile_number);
        // }

        $emailData['name'] =$userInfo['name'];
        $emailData['email']=$userInfo['email'];
        $emailData['otp']   = $userOTP;

        sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'otp_variable',$emailData);        

        return $userOTP;
    }

    ### Verify OTP-----------------//
    public function verifyOtp($user_id, $postOtp){
		$mins = getGeneralSetting('otp_expiry_duration');
		$startTime = date("Y-m-d H:i:s", strtotime('-'.$mins.' minutes', strtotime(date('Y-m-d H:i:s'))));
		$otpData = $this
			->where(DB::raw('md5(user_id)'), $user_id)
			->where('otp', $postOtp)
			->where('created_at', '>=', $startTime)
			->first();

		if(isset($otpData)){
			return true;
		}
		else{
			return false;
		}
	}

}
