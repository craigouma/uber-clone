<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReferralSetting;
use App\Customer;
use App\Driver;
use Validator;

class ReferralController extends Controller
{
    
     public function get_driver_referral_message(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'driver_id' => 'required',
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = ReferralSetting::select('id','referral_message')->first();
            $code = Driver::where('id',$input['driver_id'])->value('referral_code');
        }else{
            $data = ReferralSetting::select('id','referral_message_ar as referral_message')->first();
            $code = Driver::where('id',$input['driver_id'])->value('referral_code');
        }
        return response()->json([
            "result" => $data,
            "code" => $code,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    
    
    public function sendError($message) {
        $message = $message->all();
        $response['error'] = "validation_error";
        $response['message'] = implode('',$message);
        $response['status'] = "0";
        return response()->json($response, 200);
    } 
}
