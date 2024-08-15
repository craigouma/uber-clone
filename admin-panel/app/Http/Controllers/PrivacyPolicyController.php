<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrivacyPolicy;
use App\UserType;
use Validator;

class PrivacyPolicyController extends Controller
{
    
    
    public function driver_policy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = PrivacyPolicy::select('id','title','description','status')->where('status',1)->where('user_type_id',2)->get();
        }else{
            $data = PrivacyPolicy::select('id','title_ar as title','description_ar as description','status')->where('status',1)->where('user_type_id',2)->get();
        }
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
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
