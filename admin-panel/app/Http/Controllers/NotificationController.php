<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NotificationMessage;
use Validator;

class NotificationController extends Controller
{

    public function get_driver_notification_messages(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'country_id' => 'required',
            'driver_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = NotificationMessage::select('id','title','message','image','status')->where('status',1)->where('type',2)->where('country_id',$input['country_id'])->orderBy('id', 'DESC')->get();
        }else{
            $data = NotificationMessage::select('id','title_ar as title','message_ar as message','image','status')->where('status',1)->where('type',2)->where('country_id',$input['country_id'])->orderBy('id', 'DESC')->get();
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
