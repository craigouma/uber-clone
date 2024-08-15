<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ComplaintCategory;
use App\ComplaintSubCategory;
use App\Complaint;
use Validator;

class ComplaintController extends Controller
{
    public function get_complaint_categories(Request $request)
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
            $data = ComplaintCategory::select('id','complaint_category_name','status')->where('status',1)->where('country_id',$input['country_id'])->get();
        }else{
            $data = ComplaintCategory::select('id','complaint_category_name_ar as complaint_category_name','status')->where('status',1)->where('country_id',$input['country_id'])->get();
        }
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_complaint_sub_categories(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'country_id' => 'required',
            'complaint_category_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = ComplaintSubCategory::select('id','complaint_sub_category_name','status')->where('status',1)->where('country_id',$input['country_id'])->get();
        }else{
            $data = ComplaintSubCategory::select('id','complaint_sub_category_name_ar as complaint_sub_category_name','status')->where('status',1)->where('country_id',$input['country_id'])->get();
        }
       
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_complaint(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'customer_id' => 'required',
            'driver_id' => 'required',
            'complaint_category' => 'required',
            'complaint_sub_category' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $input['status'] = 1;
        
        $data = Complaint::create($input);
       
        return response()->json([
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
