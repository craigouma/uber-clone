<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;
use App\UserType;
use Validator;

class CommonController extends Controller
{
    public function image_upload(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'image' => 'required',
            'upload_path' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/'.$input['upload_path']);
            $image->move($destinationPath, $name);
            return response()->json([
                "result" => $input['upload_path'].'/'.$name,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Sorry something went wrong',
                "status" => 0
            ]);
        }
    }
    
    public function get_zone(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'lat' => 'required',
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $zone = $this->find_in_polygon($input['lat'],$input['lng']);
        return response()->json([
            "result" => $zone,
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
