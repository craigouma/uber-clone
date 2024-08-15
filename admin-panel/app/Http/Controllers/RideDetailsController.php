<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use App\Models\TripSubType;
use App\Models\TripType;
use App\DriverVehicle;
use App\VehicleCategory;
use App\Customer;
use App\Rating;
use App\PaymentMethod;
use App\CancellationReason;
use App\TripHistory;
use Validator;

class RideDetailsController extends Controller
{
    
    public function ride_details(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'country_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $dar = Trip::where('id',$input['trip_id'])->first();
        if($dar){
        $trip['id'] = $dar->id;
        $trip['trip_id'] = $dar->trip_id;
        $trip['pickup_date'] = $dar->pickup_date;
        $trip['pickup_time'] = $dar->pickup_time;
        $trip['pickup_location_address'] = $dar->pickup_location_address;
        $trip['drop_location_address'] = $dar->drop_location_address;
        $trip['vehicle-id'] = $dar->vehicle-id;
        $driver_vehicle = DriverVehicle::where('id',$dar->vehicle-id)->where('driver_id', $dar->driver-id)->first();
        $trip['vehicle_brand'] = $driver_vehicle['brand'];
        $trip['vehicle_color'] = $driver_vehicle['color'];
        $trip['vehicle_name'] = $driver_vehicle['vehicle_name'];
        $trip['vehicle_number'] = $driver_vehicle['vehicle_number'];
        $vehicle_type= VehicleCategory::where('id',$dar->vehicle_type)->where('country_id', $input['country_id'])->first();
        $trip['vehicle_type'] = $vehicle_type['vehicle_type'];
        $trip['base_fare'] = $vehicle_type['base_fare'];
        $trip['price_per_km'] = $vehicle_type['price_per_km'];
        $customer_name = Customer::where('id',$dar->customer-id)->first();
        $trip['first_name'] = $customer_name['first_name'];
        $trip['last_name'] = $customer_name['last_name'];
        $rating = Rating::where('trip_id',$dar->id)->where('customer_id',$dar->customer-id)->where('driver_id',$dar->driver-id)->first();
        $trip['rating'] = $rating['rating'];
        $trip['rating_feedback'] = $rating['rating_feedback'];
        $trip['total'] = $dar->total;
        $trip['sub_total'] = $dar->sub_total;
        $trip['discount'] = $dar->discount;
        $payment_method = PaymentMethod::where('id',$dar->payment_method)->where('country_id', $input['country_id'])->first();
        $trip['payment_mode'] = $payment_method['payment'];
        return response()->json([
            "result" => $trip,
            "message" => 'Success',
            "count" => count($trip),
            "status" => 1
        ]);
    }
    }
    
    public function driver_ride_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required',
            'country_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $dat = TripHistory::where('driver_id',$input['driver_id'])->get();
         $j=0;
        if(sizeof($dat) > 0){
        foreach($dat as $data){
            // echo $data; exit;
        $dar = Trip::where('id',$data->trip_id)->first();
        $trip[$j]['id'] = $dar->id;
        $trip[$j]['trip_id'] = $dar->trip_id;
        $trip[$j]['pickup_date'] = $dar->pickup_date;
        $trip[$j]['pickup_time'] = $dar->pickup_time;
        $trip[$j]['pickup_location_address'] = $dar->pickup_location_address;
        $trip[$j]['drop_location_address'] = $dar->drop_location_address;
        $trip[$j]['vehicle_id'] = $dar->vehicle-id;
        $trip[$j]['total'] = $dar->total;
        $trip[$j]['status'] = $dar->status;
        $driver_vehicle = DriverVehicle::where('id',$dar->vehicle-id)->where('driver_id', $dar->driver-id)->first();
        $trip[$j]['vehicle_brand'] = $driver_vehicle['brand'];
        $trip[$j]['vehicle_color'] = $driver_vehicle['color'];
        $trip[$j]['vehicle_name'] = $driver_vehicle['vehicle_name'];
        $trip[$j]['vehicle_number'] = $driver_vehicle['vehicle_number'];
        $trip[$j]['vehicle_type'] = VehicleCategory::where('id',$dar->vehicle_type)->where('country_id', $input['country_id'])->value('vehicle_type');
        $j++;
        } 
        return response()->json([
            "result" => $trip,
            "message" => 'Success',
            "count" => count($trip),
            "status" => 1
        ]);
    }else {
        return response()->json([
            "message" => 'No trips found',
            "status" => 0
        ]);
    }
    }
    
    public function driver_ride_details(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'country_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $dar = Trip::where('id',$input['trip_id'])->first();
        if($dar){
        $trip['id'] = $dar->id;
        $trip['trip_id'] = $dar->trip_id;
        $trip['pickup_date'] = $dar->pickup_date;
        $trip['pickup_time'] = $dar->pickup_time;
        $trip['pickup_location_address'] = $dar->pickup_location_address;
        $trip['drop_location_address'] = $dar->drop_location_address;
        $trip['vehicle-id'] = $dar->vehicle-id;
        $driver_vehicle = DriverVehicle::where('id',$dar->vehicle-id)->where('driver_id', $dar->driver-id)->first();
        $trip['vehicle_brand'] = $driver_vehicle['brand'];
        $trip['vehicle_color'] = $driver_vehicle['color'];
        $trip['vehicle_name'] = $driver_vehicle['vehicle_name'];
        $trip['vehicle_number'] = $driver_vehicle['vehicle_number'];
        $vehicle_type= VehicleCategory::where('id',$dar->vehicle_type)->where('country_id', $input['country_id'])->first();
        $trip['vehicle_type'] = $vehicle_type['vehicle_type'];
        $trip['base_fare'] = $vehicle_type['base_fare'];
        $trip['price_per_km'] = $vehicle_type['price_per_km'];
        $customer_name = Customer::where('id',$dar->customer-id)->first();
        $trip['first_name'] = $customer_name['first_name'];
        $trip['last_name'] = $customer_name['last_name'];
        $rating = Rating::where('trip_id',$dar->id)->where('customer_id',$dar->customer-id)->where('driver_id',$dar->driver-id)->first();
        $trip['rating'] = $rating['rating'];
        $trip['rating_feedback'] = $rating['rating_feedback'];
        $trip['total'] = $dar->total;
        $trip['sub_total'] = $dar->sub_total;
        $trip['discount'] = $dar->discount;
        $payment_method = PaymentMethod::where('id',$dar->payment_method)->where('country_id', $input['country_id'])->first();
        $trip['payment_mode'] = $payment_method['payment'];
        return response()->json([
            "result" => $trip,
            "message" => 'Success',
            "count" => count($trip),
            "status" => 1
        ]);
    }
    }
    
    
    
    public function get_trip_type(Request $request)
    {
       $input = $request->all();
       if($input['lang'] == 'en'){
            $data = TripType::select('id','name','active_icon','inactive_icon','vehicle_mode','status')->where('status',1)->orderBy('sort','asc')->get();
       }else{
           $data = TripType::select('id','name_ar as name','active_icon','inactive_icon','vehicle_mode','status')->where('status',1)->orderBy('sort','asc')->get();
       }
       
        foreach($data as $key => $value){
            if($input['lang'] == 'en'){
                $sub_type = TripSubType::select('id','trip_sub_type')->where('trip_type',$value->id)->get();
                $sub_type_labels = TripSubType::where('trip_type',$value->id)->pluck('trip_sub_type');
            }else{
                $sub_type = TripSubType::select('id','trip_sub_type_ar as trip_sub_type')->where('trip_type',$value->id)->get();
                $sub_type_labels = TripSubType::where('trip_type',$value->id)->pluck('trip_sub_type_ar as trip_sub_type');
            }
            $data[$key]['trip_sub_type'] = $sub_type;
            $data[$key]['trip_sub_type_labels'] = $sub_type_labels;
            
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
