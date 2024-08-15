<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;
use App\UserType;
use Validator;

class FaqController extends Controller
{
    
    
    public function driver_faq(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'country_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = Faq::select('id','question','answer','status')->where('status',1)->where('user_type_id',2)->where('country_id',$input['country_id'])->get();
        }else{
            $data = Faq::select('id','question_ar as question','answer_ar as answer','status')->where('status',1)->where('user_type_id',2)->where('country_id',$input['country_id'])->get();
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
