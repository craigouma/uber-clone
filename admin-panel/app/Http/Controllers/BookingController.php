<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Faq;
use App\Trip;
use App\PromoCode;
use App\VehicleCategory;
use App\Customer;
use App\AppSetting;
use App\Country;
use App\Status;
use App\Models\DriverHiringRequest;
use App\Models\DriverHiringStatus;
use App\Currency;
use App\Models\MissedTripRequest;
use App\Models\DriverTripRequest;
use App\Models\CustomerPromoHistory;
use App\Models\DriverTip;
use App\PaymentMethod;
use App\DriverEarning;
use App\DriverWalletHistory;
use App\BookingStatus;
use App\PaymentHistory;
use App\Driver;
use App\TripCancellation;
use App\DriverVehicle;
use App\TripSetting;
use App\TripRequest;
use App\UserType;
use App\Models\ScratchCardSetting;
use App\Models\CustomerOffer;
use App\Models\LuckyOffer;
use App\InstantOffer;
use App\TripType;
use Validator;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use DateTime;
use DateTimeZone;
use App\CustomerWalletHistory;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;
use App\Models\Stop;
use Cartalyst\Stripe\Stripe;
class BookingController extends Controller
{
    public function find_driver($trip_request_id){
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        $trip_request = TripRequest::where('id',$trip_request_id)->first();
        $drivers = $database->getReference('drivers/'.$trip_request->vehicle_type)
                    ->getSnapshot()->getValue();
        $rejected_drivers = DriverTripRequest::where('trip_request_id',$trip_request_id)->where('status',4)->pluck('driver_id')->toArray();
        $min_earning = 0;
        $min_driver_id = 0;
        $booking_searching_radius = TripSetting::value('booking_searching_radius');
        $shared_trip_settings = DB::table('shared_trip_settings')->first();
        $shared_ride_mode = 0;
        $i=0;
        $tt_name = '';
        if($trip_request->trip_type != 5){
            foreach($drivers as $key => $value){
                if($value && array_key_exists('driver_id', $value)){
                    if (!in_array($value['driver_id'], $rejected_drivers)){
                        $distance = $this->distance($trip_request->pickup_lat, $trip_request->pickup_lng, $value['geo']['lat'], $value['geo']['lng'], 'K') ;
                        $driver_wallet = Driver::where('id',$value['driver_id'])->value('wallet');
                        if($distance <= $booking_searching_radius && $value['online_status'] == 1 && $value['booking']['booking_status'] == 0 && $driver_wallet > 0){
                            $earning = $this->get_today_driver_earnings($value['driver_id']);
                            if($min_earning == 0 && $i == 0){
                                $min_earning = $earning;
                                $min_driver_id = $value['driver_id'];
                            }else if($earning < $min_earning){
                                $min_earning = $earning;
                                $min_driver_id = $value['driver_id'];
                            }
                            $i++;
                        }
                    }
                }
            }    
        }else{
            $tt_name = 'Shared';
            foreach($drivers as $key => $value){
                if($value && array_key_exists('driver_id', $value)){
                    if (!in_array($value['driver_id'], $rejected_drivers)){
                        $check = $this->check_shared_nearest_driver($value['driver_id'], $trip_request->pickup_lat, $trip_request->pickup_lng, $trip_request->drop_lat, $trip_request->drop_lng, $value, $booking_searching_radius, $shared_trip_settings);
                        if($check != 0 && $min_driver_id == 0){
                            $min_driver_id = $value['driver_id'];
                            $shared_ride_mode = 1;
                        }else if($check == 2){
                            $min_driver_id = $value['driver_id'];
                            $shared_ride_mode = 2;
                        }
                    }
                }
            }
        }
        if($min_driver_id == 0){
            $this->add_missed_trip_request($trip_request->customer_id,$trip_request->pickup_lat,$trip_request->pickup_lng,$trip_request->zone);
            $newPost = $database
            ->getReference('customers/'.$trip_request->customer_id)
            ->update([
                'booking_id' => 0,
                'booking_status' => 0,
                'is_searching' => 0
            ]);
            return 0;
        }
        if($trip_request->trip_type == 2){
            $trip_request->drop_address = "Sorry, customer not mentioned";
        }
        
        TripRequest::where('id',$trip_request->id)->update([ 'driver_id' => $min_driver_id ]);
        if($trip_request->trip_type == 5 && $shared_ride_mode == 2){
            $newPost = $database
            ->getReference('shared/'.$min_driver_id)
            ->update([
                'booking_id' => $trip_request->id,
                'pickup_address' => $trip_request->pickup_address,
                'drop_address' => $trip_request->drop_address,
                'total' => $trip_request->total,
                'customer_name' => Customer::where('id',$trip_request->customer_id)->value('first_name')
            ]);
        }else{
            $newPost = $database
            ->getReference('drivers/'.$trip_request->vehicle_type.'/'.$min_driver_id.'/booking')
            ->update([
                'booking_id' => $trip_request->id,
                'booking_status' => 1,
                'trip_type' => $tt_name
            ]);
        }     
        return $trip_request->id;
    }
    
    public function check_shared_nearest_driver($driver_id, $p_lat, $p_lng, $d_lat, $d_lng, $val, $booking_searching_radius, $shared_trip_settings){
        $driver = DB::table('drivers')->where('id',$driver_id)->first();
     
        if($driver->wallet > 0 && $driver->shared_ride_status == 1 && $val['online_status'] == 1 && $val['booking']['booking_status'] == 0){
            
            $distance = $this->distance($p_lat, $p_lng, $val['geo']['lat'], $val['geo']['lng'], 'K');
            if($distance <= $booking_searching_radius){
                return 1;
            }else{
                return 0;
            }
            
        }else if($driver->wallet > 0 && $driver->shared_ride_status == 1 && $val['online_status'] == 1 && $val['booking']['booking_status'] == 2 && $val['booking']['trip_type'] == "Shared"){
            $trip_locations = DB::table('trips')->where('driver_id',$driver_id)->where('status','<', 4)->select('pickup_lat','pickup_lng','drop_lat','drop_lng')->get();
            
            if($trip_locations->count()){
                foreach($trip_locations as $key => $value){
                    $pickup_distance = $this->distance($p_lat, $p_lng, $val['geo']['lat'], $val['geo']['lng'], 'K');
                    $drop_distance = $this->distance($d_lat, $d_lng, $value->drop_lat, $value->drop_lng, 'K');
                    if($pickup_distance <= $shared_trip_settings->pickup_radius && $drop_distance <= $shared_trip_settings->drop_radius){
                        return 2;
                    }else {
                        return 0;
                    }
                }
            }else{
                return 2;
            }
        }else{
            return 0;
        }
    }
    
    public function get_today_driver_earnings($driver_id){
         $data = DriverEarning::where('driver_id',$driver_id)->whereDate('created_at', DB::raw('CURDATE()'))->sum('amount');
         return $data;
    }
    
    public function ride_later(){
        $data = TripRequest::select('id','pickup_date')->where('status',2)->where('booking_type',2)->orderBy('pickup_date')->get();
    
        $timeout_trip_time = 15;
        $future_trip_time = 30;

        foreach($data as $key => $value){
            $value->pickup_date = date("Y-m-d H:i:s", strtotime($value->pickup_date));
            $current_date = $this->get_date();
            
            $interval_time = $this->date_difference($value->pickup_date,$current_date);
            
            if($value->pickup_date > $current_date) {
                if($interval_time <= $future_trip_time){
                    $this->find_driver($value->id);
                }
            }else{
                if($interval_time <= $timeout_trip_time){
                    $this->find_driver($value->id);
                }else{
                    TripRequest::where('id',$value->id)->update([ 'status' => 5 ]);
                }
            }
        }

    }
    
    public function get_date(){
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }
    
    public function date_difference($date1,$date2){
        $date1=date_create($date1);
        $date2=date_create($date2);
        $diff=date_diff($date1,$date2);
        $days = $diff->format("%a");
        $hours = $diff->format("%h");
        $min = $diff->format("%i");
        
        $minutes = $days * 24 * 60;
        $minutes += $hours * 60;
        $minutes += $min;
        return $minutes;
    }
    
    /*public function get_fare(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_type' => 'required',
            'km' => 'required',
            'vehicle_type' => 'required',
            'promo' => 'required',
            'country_id' => 'required',
            'surge' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['trip_type'] == 1){
            $fares = $this->calculate_daily_fare($input['vehicle_type'],$input['km'],$input['promo'],$input['country_id'],$input['surge']);
        }else if($input['trip_type'] == 2){
            $fares = $this->calculate_rental_fare($input['vehicle_type'],$input['package_id'],$input['promo'],$input['country_id'],0,0,$input['surge']);
        }else if($input['trip_type'] == 3){
            $fares = $this->calculate_outstation_fare($input['vehicle_type'],$input['km'],$input['promo'],$input['country_id'],$input['days'],$input['trip_sub_type'],$input['surge']);
        }else if($input['trip_type'] == 4){
            $fares = $this->calculate_delivery_fare($input['vehicle_type'],$input['km'],$input['promo'],$input['country_id'],$input['days'],$input['trip_sub_type'],$input['surge']);
        }else if($input['trip_type'] == 5){
            $fares = $this->calculate_shared_fare($input['vehicle_type'],$input['km'],$input['promo'],$input['country_id'],$input['surge']);
        }
        
        
        return response()->json([
            "result" => $fares,
            "message" => 'Success',
            "status" => 1
        ]);
        
    }*/
    
    /*public function calculate_daily_fare($vehicle_type,$km,$promo,$country_id,$surge){
        
        $data = [];
        $vehicle = DB::table('daily_fare_management')->where('country_id',$country_id)->where('vehicle_type',$vehicle_type)->first();
        
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $additional_fare = number_format((float)$data['km'],2,'.','') * number_format((float)$data['price_per_km'],2,'.','');
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('country_id',$country_id)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        $data['surge'] = $surge;
        $data['total_fare'] = $data['total_fare'] * $surge;
        $data['total_fare'] = number_format((float)$data['total_fare'], 2, '.', '');
        
        return $data;
    }
    
    public function calculate_rental_fare($vehicle_type,$package_id,$promo,$country_id,$extra_km,$extra_hour,$surge){
        
        $data = [];
        $package_price = DB::table('rental_fare_management')->where('country_id',$country_id)->where('package_id',$package_id)->first();
        
        if(is_object($package_price)){
            $data['price_per_km'] = number_format((float)$package_price->price_per_km, 2, '.', '');
            $data['price_per_hour'] = number_format((float)$package_price->price_per_hour, 2, '.', '');
            $data['base_fare'] = number_format((float)$package_price->package_price, 2, '.', '');
            
            $additional_km_fare = $extra_km * $data['price_per_km'];
            $data['additional_km_fare'] = number_format((float)$additional_km_fare, 2, '.', '');
            $additional_hour_fare = $extra_hour * $data['price_per_hour'];
            $data['additional_hour_fare'] = number_format((float)$additional_hour_fare, 2, '.', '');
            
            $data['price_per_hour'] = number_format((float)$package_price->price_per_hour, 2, '.', '');
            $fare = $data['additional_km_fare'] + $data['additional_hour_fare'] + $data['base_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('country_id',$country_id)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        $data['surge'] = $surge;
        $data['total_fare'] = $data['total_fare'] * $surge;
        $data['total_fare'] = number_format((float)$data['total_fare'], 2, '.', '');
        
        return $data;
    }
    
    public function calculate_outstation_fare($vehicle_type,$km,$promo,$country_id,$days,$trip_sub_type,$surge){
        
        $data = [];
        $vehicle = DB::table('outstation_fare_management')->where('country_id',$country_id)->where('vehicle_type',$vehicle_type)->where('trip_sub_type_id',$trip_sub_type)->first();
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $data['driver_allowance'] = number_format((float)$vehicle->driver_allowance, 2, '.', '');
            $data['driver_allowance'] = $data['driver_allowance'] * $days;
            $additional_fare = number_format($data['km']) * $data['price_per_km'];
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'] + $data['driver_allowance'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('country_id',$country_id)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        $data['surge'] = $surge;
        $data['total_fare'] = $data['total_fare'] * $surge;
        $data['total_fare'] = number_format((float)$data['total_fare'], 2, '.', '');
        
        return $data;
    }
    
    public function calculate_delivery_fare($vehicle_type,$km,$promo,$country_id,$days,$trip_sub_type,$surge){
        
        $data = [];
        $vehicle = DB::table('delivery_fare_management')->where('country_id',$country_id)->where('vehicle_type',$vehicle_type)->where('trip_sub_type_id',$trip_sub_type)->first();
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $data['driver_allowance'] = number_format((float)$vehicle->driver_allowance, 2, '.', '');
            $data['driver_allowance'] = $data['driver_allowance'] * $days;
            $additional_fare = number_format($data['km']) * $data['price_per_km'];
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'] + $data['driver_allowance'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('country_id',$country_id)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        $data['surge'] = $surge;
        $data['total_fare'] = $data['total_fare'] * $surge;
        $data['total_fare'] = number_format((float)$data['total_fare'], 2, '.', '');
        
        return $data;
    }
    
    public function calculate_shared_fare($vehicle_type,$km,$promo,$country_id,$surge){
        
        $data = [];
        $vehicle = DB::table('shared_fare_management')->where('country_id',$country_id)->where('vehicle_type',$vehicle_type)->first();
        
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $additional_fare = number_format((float)$data['km'],2,'.','') * number_format((float)$data['price_per_km'],2,'.','');
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('country_id',$country_id)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        $data['surge'] = $surge;
        $data['total_fare'] = $data['total_fare'] * $surge;
        $data['total_fare'] = number_format((float)$data['total_fare'], 2, '.', '');
        
        return $data;
    }*/
    
    public function apply_discount($promo_id,$total_fare){
        $data = [];
        $promo = DB::table('promo_codes')->where('id',$promo_id)->first();
        if($promo->min_fare > $total_fare){
            $data['discount'] = 0.00;
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
            return $data;
        }
        
        if($promo->promo_type == 5){
            $total_fare = $total_fare - $promo->discount;
            if($total_fare > 0){
                $data['discount'] = number_format((float)$promo->discount, 2, '.', '');
                $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
            }else{
                $data['discount'] = number_format((float)$total_fare, 2, '.', '');
                $data['total_fare'] = 0.00;
            }
        }else{
            $discount = ($promo->discount / 100) * $total_fare;
            if($discount > $promo->max_discount_value){
                $discount = $promo->max_discount_value;
            }
            $total_fare = $total_fare - $discount;
            $data['discount'] = number_format((float)$discount, 2, '.', '');
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        return $data;
    }
    
    public function driver_bookings(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
         if($input['lang'] == 'en'){
            if($input['filter'] == 1){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type')
                    ->where('trips.driver_id',$input['driver_id'])
                    ->whereIn('trips.status',[1,2,3,4])->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 2){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type')
                    ->where('trips.driver_id',$input['driver_id'])
                    ->where('trips.status',5)->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 3){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type')
                    ->where('trips.driver_id',$input['driver_id'])
                    ->whereIn('trips.status',[6,7])->orderBy('id', 'DESC')
                    ->get();
            }
            
        }else{
            if($input['filter'] == 1){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name_ar as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type_ar as vehicle_type')
                    ->where('trips.driver_id',$input['driver_id'])
                    ->whereIn('trips.status',[1,2,3,4])->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 2){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name_ar as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type_ar as vehicle_type')
                    ->where('trips.driver_id',$input['driver_id'])
                    ->where('trips.status',5)->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 3){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name_ar as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type_ar as vehicle_type')
                    ->where('trips.driver_id',$input['driver_id'])
                    ->whereIn('trips.status',[6,7])->orderBy('id', 'DESC')
                    ->get();
            }
        }
                
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function check_subscription_trip($trip_id,$trip_type,$customer_id){
        if($trip_type == 5){
            $customer = DB::table('customers')->where('id',$customer_id)->first();
            if($customer->current_sub_id && $customer->subscription_trips > 0){
                Trip::where('id',$trip_id)->update([ 'is_subscription_trip' => 1 ]);
                //Customer::where('id',$customer_id)->update([ 'is_subscription_trip' => $customer->subscription_trips - 1 ]);
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    
    public function update_promo_histories($promo_id,$customer_id){
        if($promo_id){
            CustomerPromoHistory::create([ 'customer_id' => $customer_id, "promo_id" =>$promo_id ]);
        }
    }
    
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);
      if ($unit == "K") {
          return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
      } else {
          return $miles;
      }
    }
    
    public function calculate_earnings($trip_id){
        $trip = Trip::where('id',$trip_id)->first();
        $payment_method = PaymentMethod::where('id',$trip->payment_method)->first();
        $admin_commission_percent = TripSetting::value('admin_commission');
        $total = $trip->total;
        $total = number_format((float)$total, 2, '.', '');
        $admin_commission = ($admin_commission_percent / 100) * $total;
        $admin_commission = number_format((float)$admin_commission, 2, '.', '');
        $vendor_commission = $total - $admin_commission;
        $vendor_commission = number_format((float)$vendor_commission, 2, '.', '');
        
        DriverEarning::create([ 'trip_id' => $trip_id, 'driver_id' => $trip->driver_id, 'amount' => $vendor_commission ]);
        $old_wallet = Driver::where('id',$trip->driver_id)->value('wallet');
        
        if ($payment_method->payment_type == 2) {
            $this->add_driver_wallet_history($trip->driver_id,1,$trip->id,$vendor_commission);
            $new_wallet = $old_wallet + $vendor_commission;
        }else if ($payment_method->payment_type == 1) {
           $this->add_driver_wallet_history($trip->driver_id,2,$trip->id,$admin_commission);
           $new_wallet = $old_wallet - $admin_commission;
        }else if ($payment_method->payment_type == 3) {
            $wallet_payment = PaymentHistory::where('trip_id',$trip->id)->where('mode','Wallet')->value('amount');
            $this->add_driver_wallet_history($trip->driver_id,1,$trip->id,$wallet_payment);
            $secondry_wallet = $old_wallet + $wallet_payment;
            $secondry_wallet = number_format((float)$secondry_wallet, 2, '.', '');
            Driver::where('id',$trip->driver_id)->update([ 'wallet' => $secondry_wallet ]);
            $this->add_driver_wallet_history($trip->driver_id,2,$trip->id,$admin_commission);
            $new_wallet = $secondry_wallet - $admin_commission;
        }else if($payment_method->payment_type == 4) {
            $this->add_driver_wallet_history($trip->driver_id,1,$trip->id,$vendor_commission);
            $new_wallet = $old_wallet + $vendor_commission;
        }
        if($trip->tip){
            $this->add_driver_wallet_history($trip->driver_id,1,$trip->id,$trip->tip);
            $new_wallet = $new_wallet + $trip->tip;
            DriverTip::create([ 'driver_id' => $trip->driver_id, 'customer_id' => $trip->customer_id, 'tip' => $trip->tip ,'trip_id' => $trip->id, 'tip_mode' => $trip->payment_method ]);
        }
        
        $new_wallet = number_format((float)$new_wallet, 2, '.', '');
        Driver::where('id',$trip->driver_id)->update([ 'wallet' => $new_wallet ]);
    }

    public function add_driver_wallet_history($driver_id,$type,$trip_id,$amount){
        if($type == 1){
            $message = 'credited to your account for the booking '.$trip_id;
            $message_ar = 'ضة  اب لطل '.$trip_id;
        }else if($type == 2){
            $message = 'debited from your account for the booking '.$trip_id;
            $message_ar = 'إ ل سابك للط '.$trip_id;
        }else{
            $message = 'credited your tip for the booking '.$trip_id;
            $message_ar = 'ات ملا حلة '.$trip_id;
            $type=1;
        }
        DriverWalletHistory::create([ 'driver_id' => $driver_id, 'type' => $type, 'message' => $message,'message_ar' => $message_ar, 'amount' => $amount ]);
    }
    
    public function direct_booking(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'phone_number' => 'required',
            'customer_name' => 'required',
            'pickup_address' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'drop_address' => 'required',
            'drop_lat' => 'required',
            'drop_lng' => 'required',
            'driver_id' => 'required',
            'km' => 'required'
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $driver = Driver::where('id',$input['driver_id'])->first();
        $vehicle = DriverVehicle::where('driver_id',$input['driver_id'])->first();
        $customer = Customer::where('phone_number',$input['phone_number'])->first();
        if(!is_object($customer)){
            
            $country = Country::where('id',$driver->country_id)->first();
            $currency = Currency::where('country_id',$country->id)->first();
            
            $customer['first_name'] = $input['customer_name'];
            $customer['country_id'] = $country->id;
            $customer['country_code'] = $country->phone_code;
            $customer['currency'] = $currency->currency;
            $customer['currency_short_code'] = $currency->currency_short_code;
            $customer['phone_number'] = $input['phone_number'];
            $customer['phone_with_code'] = $country->phone_code.$input['phone_number'];
            $customer['status'] = 1;
            Customer::create($customer);
            $customer = Customer::where('phone_number',$input['phone_number'])->first();
        }
        
        $data['km'] = $input['km'];
        $data['vehicle_type'] = $vehicle->vehicle_type;
        $data['customer_id'] = $customer->id;
        $data['booking_type'] = 2;
        $data['promo'] = 0;
        $data['zone'] = $driver->zone;
        $data['country_id'] = $customer->country_id;
        $data['payment_method'] = PaymentMethod::where('country_id',$customer->country_id)->where('payment_type',1)->value('id');
        $data['pickup_address'] = $input['pickup_address'];
        $data['pickup_lat'] = $input['pickup_lat'];
        $data['pickup_lng'] = $input['pickup_lng'];
        $data['drop_address'] = $input['drop_address'];
        $data['drop_lat'] = $input['drop_lat'];
        $data['drop_lng'] = $input['drop_lng'];
        
         $url = 'https://maps.googleapis.com/maps/api/staticmap?center='.$input['pickup_lat'].','.$input['pickup_lng'].'&zoom=16&size=600x300&maptype=roadmap&markers=color:red%7Clabel:L%7C'.$input['pickup_lat'].','.$input['pickup_lng'].'&key='.env('MAP_KEY');
            $img = 'trip_request_static_map/'.md5(time()).'.png';
        file_put_contents('uploads/'.$img, file_get_contents($url));
        
        $fares = $this->calculate_daily_fare($data['vehicle_type'],$data['km'],$data['promo']);
        
        $booking_request = $data;
        $booking_request['distance'] = $data['km'];
        unset($booking_request['km']);
        $booking_request['total'] = $fares['total_fare'];
        $booking_request['sub_total'] = $fares['fare'];
        $booking_request['discount'] = $fares['discount'];
        $booking_request['tax'] = $fares['tax'];
        $booking_request['trip_type'] = 1;
        $booking_request['booking_type'] = 1;
        $booking_request['package_id'] = 0;
        $booking_request['static_map'] = $img;
        $booking_request['pickup_date'] = date("Y-m-d H:i:s");
        $id = TripRequest::create($booking_request)->id;
        //print_r($id);exit;
        
        //$factory = (new Factory)->withServiceAccount(config_path().'/'.env('FIREBASE_FILE'));
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        $newPost = $database
        ->getReference('customers/'.$data['customer_id'])
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
            'is_searching' => 0
        ]);
        
        $newPost = $database
        ->getReference('drivers/'.$data['vehicle_type'].'/'.$input['driver_id'].'/booking')
        ->update([
            'booking_id' => $id,
            'booking_status' => 1,
        ]);
        
        //$this->auto_trip_accept($id,$input['driver_id']);
        return response()->json([
            "result" => $id,
            "message" => 'Success',
            "status" => 1
        ]);
        
    }
    
    public function auto_trip_accept($trip_id,$driver_id)
    {
        $input['trip_id'] = $trip_id;
        $input['driver_id'] = $driver_id;
        
        $trip = TripRequest::where('id',$input['trip_id'])->first()->toArray();
        $customer_id = TripRequest::where('id',$input['trip_id'])->value('customer_id');
        $phone_with_code = Customer::where('id',$customer_id)->value('phone_with_code');
        
        $data = $trip;
        $data['driver_id'] = $input['driver_id'];
        $data['promo_code'] = $data['promo'];
        unset($data['promo']);
        unset($data['id']);
        $data['pickup_date'] = $trip['pickup_date'];
        $data['country_id'] = $trip['country_id'];
        $data['package_id'] = $trip['package_id'];
        $data['trip_type'] = $trip['trip_type'];
        $data['status'] = 1;
        $data['vehicle_type'] = $trip['vehicle_type'];
        $data['vehicle_id'] = DB::table('driver_vehicles')->where('driver_id',$input['driver_id'])->value('id');
        $data['otp'] = $otp = rand(1000,9999);
        $message = "Hi ".env('APP_NAME')." , Your OTP code is:  ".$data['otp'];
            $this->sendSms($phone_with_code,$message);
        $id = Trip::create($data)->id;
        
        $trip_id = str_pad($id,6,"0",STR_PAD_LEFT);
        Trip::where('id',$id)->update([ 'trip_id' => $trip_id ]);
        
        
        //Firebase 
        //$factory = (new Factory)->withServiceAccount(config_path().'/'.env('FIREBASE_FILE'));
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        
        
        $trip_details = Trip::where('id',$id)->first();
        $customer = Customer::where('id',$trip_details->customer_id)->first();
        $driver = Driver::where('id',$trip_details->driver_id)->first();
        $vehicle = DriverVehicle::where('id',$trip_details->vehicle_id)->first();
        $current_status = BookingStatus::where('id',1)->first();
        $new_status = BookingStatus::where('id',2)->first();
        $payment_method = PaymentMethod::where('id',$trip_details->payment_method)->value('Payment');
        
        $data['driver_id'] = $input['driver_id'];
        $data['trip_request_id'] = $input['trip_id'];
        $data['status'] = 3;
        
        DriverTripRequest::create($data);
        
        //Trip Firebase 
        $data = [];
        $data['id'] = $id;
        $data['trip_id'] = $trip_id;
        $data['trip_type'] = $trip_details->trip_type;
        $data['customer_id'] = $trip_details->customer_id;
        $data['customer_name'] = $customer->first_name;
        $data['customer_profile_picture'] = $customer->profile_picture;
        $data['customer_phone_number'] = $customer->phone_number;
        $data['driver_id'] = $trip_details->driver_id;
        $data['driver_name'] = $driver->first_name;
        $data['driver_profile_picture'] = $driver->profile_picture;
        $data['driver_phone_number'] = $driver->phone_number;
        $data['pickup_address'] = $trip_details->pickup_address;
        $data['pickup_lat'] = $trip_details->pickup_lat;
        $data['pickup_lng'] = $trip_details->pickup_lng;
        $data['drop_address'] = $trip_details->drop_address;
        $data['drop_lat'] = $trip_details->drop_lat;
        $data['drop_lng'] = $trip_details->drop_lng;
        $data['payment_method'] = $payment_method;
        $data['pickup_date'] = $trip_details->pickup_date;
        $data['total'] = $trip_details->total;
        $data['collection_amount'] = $trip_details->total;
        $data['vehicle_id'] = $trip_details->vehicle_id;
        $data['otp'] = $trip_details->otp;
        $data['vehicle_image'] = $vehicle->vehicle_image;
        $data['vehicle_number'] = $vehicle->vehicle_number;
        $data['vehicle_color'] = $vehicle->color;
        $data['vehicle_name'] = $vehicle->vehicle_name;
        $data['customer_status_name'] = $current_status->customer_status_name;
        $data['driver_status_name'] = $current_status->status_name;
        $data['status'] = 1;
        $data['new_driver_status_name'] = $new_status->status_name;
        $data['new_status'] = 2;
        $data['driver_lat'] = 0;
        $data['driver_lng'] = 0;
        $data['bearing'] = 0;
        
        $newPost = $database
        ->getReference('trips/'.$trip_details->id)
        ->update($data);
        
        $newPost = $database
        ->getReference('customers/'.$trip['customer_id'])
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
            'is_searching' => 0,
        ]);
        
        $newPost = $database
        ->getReference('drivers/'.$trip['vehicle_type'].'/'.$input['driver_id'].'/booking')
        ->update([
            'booking_id' => $id,
            'booking_status' => 2
        ]);
        
        TripRequest::where('id',$input['trip_id'])->update([ 'status' => 3 ]);
        
        if($customer->fcm_token != "" && $customer->fcm_token != "123456"){
        $this->send_fcm($current_status->status_name,$current_status->customer_status_name,$customer->fcm_token);
        }
        
        return response()->json([
            "result" => $id,
            "message" => 'Success',
            "status" => 1
        ]);
        
    }
    
    public function create_reward($trip_id){
        $trip = Trip::where('id',$trip_id)->first();
        $scratch_settings = ScratchCardSetting::first();
        if($scratch_settings->coupon_type == 2){
            $rand = rand(10,100);
            if ($rand % 2 == 0) {
                $data['customer_id'] = $trip->customer_id;
                $data['title'] = "Better Luck Next Time !";
                $data['description'] = "Take Advantage of this Amazing offer before. Now it's too late. Better Luck next time";
                $data['view_status'] = 0;
                $data['image'] = "rewards/better_luck.png";
                $data['type'] = 0;
                $data['ref_id'] = 0;
                $data['status'] = 1;
                CustomerOffer::create($data);
                return true;
            }
        }
        $lucky_offer_limit = $scratch_settings->lucky_offer;
        $last_lucky_offer = CustomerOffer::where('type',2)->orderBy('id', 'DESC')->value('id');
        $last_offer = CustomerOffer::orderBy('id', 'DESC')->value('id');
        $lucky_find = $last_offer - $last_lucky_offer;
        
        if($lucky_find >= $lucky_offer_limit){
            $lucky_status = 1;
        }else{
            $lucky_status = 0;
        }
        
        if($lucky_status == 1){
            $lucky_count = LuckyOffer::count();
            $lucky_id = rand(1,$lucky_count);
            $lucky = LuckyOffer::where('id',$lucky_id)->first();
            $data['customer_id'] = $trip->customer_id;
            $data['title'] = $lucky->offer_name;
            $data['description'] = $lucky->offer_description;
            $data['image'] = $lucky->image;
            $data['ref_id'] = $lucky_id;
            $data['view_status'] = 0;
            $data['type'] = 2;
            $data['status'] = 1;
            CustomerOffer::create($data);
        }else{
            $instant_count = InstantOffer::count();
            $instant_id = rand(1,$instant_count);
            $instant = InstantOffer::where('id',$instant_id)->first();
            $data['customer_id'] = $trip->customer_id;
            $data['title'] = $instant->offer_name;
            $data['description'] = $instant->offer_description;
            $data['view_status'] = 0;
            $data['ref_id'] = $instant_id;
            $data['image'] = "rewards/reward_image.png";
            $data['type'] = 1;
            $data['status'] = 1;
            CustomerOffer::create($data);
        }
        return true;
    }
    
    public function spot_booking_otp(Request $request){

         $input = $request->all();
        $validator = Validator::make($input, [
            'phone_number' => 'required',
            'customer_name' => 'required',
            'pickup_address' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'drop_address' => 'required',
            'drop_lat' => 'required',
            'drop_lng' => 'required',
            'driver_id' => 'required',
            'km' => 'required',
            'fare' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $driver = Driver::where('id',$input['driver_id'])->first();
        $vehicle = DriverVehicle::where('driver_id',$input['driver_id'])->first();
        $country = Country::where('id',$driver->country_id)->first();
        $phone_with_code = $country->phone_code.$input['phone_number'];
        $currency = Currency::where('country_id',$country->id)->value('currency');
        //print_r($currency);exit;
        
        $data['km'] = $input['km'];
        $data['vehicle_type'] = $vehicle->vehicle_type;
        $data['booking_type'] = 2;
        $data['promo'] = 0;
        $data['country_id'] = $driver->country_id;
        
        //$data['fare'] = [];
           $fares = $this->calculate_daily_fare($data['vehicle_type'],$data['km'],$data['promo']); 
            $data['otp'] = rand(1000,9999);
            $message = "Hi ".env('APP_NAME')." , Your OTP code is:  ".$data['otp'].". Pickup location:  ".$input['pickup_address'].", Drop location:" .$input['drop_address'].". Total fare:" .$currency.$input['fare'];
            $this->sendSms($phone_with_code,$message);
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
       

    }
    public function send_invoice(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'country_code' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
            $booking_details = Trip::where('id',$input['id'])->first();
            $customer = Customer::where('id',$booking_details->customer_id)->first();
            $email = $customer->email;
            $app_setting = AppSetting::first();
            $data = array();
            $data['logo'] = $app_setting->logo;
            $data['booking_id'] = $booking_details->trip_id;
            $data['customer_name'] = Customer::where('id',$booking_details->customer_id)->value('first_name');
            $data['pickup_address'] = $booking_details->pickup_address;
            $data['drop_address'] = $booking_details->drop_address;
            $data['start_time'] = $booking_details->start_time;
            $data['end_time'] = $booking_details->end_time;
            
            
            $data['driver'] = (Driver::where('id',$booking_details->driver_id)->value('first_name') != '' ) ? Driver::where('id',$booking_details->driver_id)->value('first_name') : "---" ;
            $country = Country::where('phone_code',$input['country_code'])->value('id');
            $data['country_id'] = $country;
            $data['currency'] = Currency::where('country_id',$data['country_id'])->value('currency');
           
            $data['payment_method'] = PaymentMethod::where('id',$booking_details->payment_method)->value('payment');
            $data['sub_total'] = $booking_details->sub_total;
            $data['discount'] =  $booking_details->discount;
            $data['total'] =  $booking_details->total;
            $data['tax'] =  $booking_details->tax;
            $data['status'] =  Status::where('id',$booking_details->status)->value('name');
            
            $mail_header = array("data" => $data);
            $this->send_order_mail($mail_header,'Enjoy the ride',$email);
            return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);

    }
    
    public function get_access_token(Request $request){
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => 'required',
            'trip_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
       $data = $this->generate_twillio_key();
       // Required for all Twilio access tokens
        $twilioAccountSid = env('TWILIO_SID');
        $twilioApiKey = $data['key'];
        $twilioApiSecret = $data['secret'];
        
        // Required for Voice grant
        $outgoingApplicationSid = $this->create_app_sid($input['type'],$input['trip_id']);
        
        // An identifier for your app - can be anything you'd like
        $identity = 'WeCare';
        
        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            $identity
        );
        
        // Create Voice grant
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);
        
        // Optional: add to allow incoming calls
        $voiceGrant->setIncomingAllow(true);
        
        // Add grant to token
        $token->addGrant($voiceGrant);
        
        return response()->json([
            "result" => $token->toJWT(),
            "outgoing_id" => $outgoingApplicationSid,
            "message" => 'Success',
            "status" => 1
        ]);
   }


   public function generate_twillio_key(){
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);
        
        $new_key = $twilio->newKeys
                          ->create();
        
        $data['key'] = $new_key->sid;
        $data['secret'] = $new_key->secret;
        
        return $data;
        
   }
   
   public function create_app_sid($type,$trip_id){
       $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');
       $twilio = new Client($sid, $token);
        
        $application = $twilio->applications
                              ->create([
                                           "voiceMethod" => "GET",
                                           "voiceUrl" => env('APP_URL')."/api/make_call/".$type.'/'.$trip_id,
                                           "friendlyName" => "Phone Me"
                                       ]
                              );
       return $application->sid;
       
   }
   
   public function make_call($type,$trip_id){
       
       $response = new VoiceResponse();
       $phone_number = "";
       if($type == 1){
           $phone_number = DB::table('trips')
                            ->join('drivers', 'drivers.id', '=', 'trips.driver_id')
                            ->where('trips.id',$trip_id)->value('drivers.phone_with_code');
       }else{
           $phone_number = DB::table('trips')
                            ->join('customers', 'customers.id', '=', 'trips.customer_id')
                            ->where('trips.id',$trip_id)->value('customers.phone_with_code');
       }
       $response->say('Welcome to tuk tuk taxi service, please wait we will connect the call', ['voice' => 'woman', 'language' => 'ar-Ar']);
        $dial = $response->dial('', ['callerId' => env('TWILIO_FROM')]);
        $dial->number($phone_number);
        $response->say('Thanks for calling us', ['voice' => 'woman', 'language' => 'ar-Ar']);
        echo $response;
   }
   
   public function verify_caller_id(){
       $sid    = env('TWILIO_SID');
       $token  = env('TWILIO_TOKEN');
       $twilio = new Client($sid, $token);
        
        $validation_request = $twilio->validationRequests
                                     ->create("+919789354285",
                                              ["friendlyName" => "Sarath"]
                                     );
        
        print($validation_request->friendlyName);
   }
    
    public function get_stops(Request $request){
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data = DB::table('stops')->where('trip_id',$input['trip_id'])->get();
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    
    public function trip_request_cancel_by_customer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_request_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        
        $trip = TripRequest::where('id',$input['trip_request_id'])->first();
        
        //Firebase 
        //$factory = (new Factory)->withServiceAccount(config_path().'/'.env('FIREBASE_FILE'));
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        $newPost = $database
        ->getReference('customers/'.$trip->customer_id)
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
            'is_searching' => 0
        ]);
        
        //$vehicle_type = DriverVehicle::where('id',$trip->vehicle_type)->value('vehicle_type');
        $newPost = $database
        ->getReference('drivers/'.$trip->vehicle_type.'/'.$trip->driver_id.'/booking')
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
        ]);
        
        TripRequest::where('id',$input['trip_request_id'])->update([ 'status' => 6 ]);
        
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
        
    }
    
    public function get_ongoing_trip_details(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $result = DB::table('trips')
                    ->join('customers','customers.id','=','trips.customer_id')
                    ->join('drivers','drivers.id','=','trips.driver_id')
                    ->join('booking_statuses','booking_statuses.id','=','trips.status')
                    ->join('driver_vehicles','driver_vehicles.driver_id','=','trips.driver_id')
                    ->where('trips.id',$input['trip_id'])
                    ->select('trips.*','customers.first_name as customer_name','customers.phone_with_code as customer_phone_number','customers.profile_picture as customer_profile_picture','booking_statuses.customer_status_name','booking_statuses.customer_status_name_ar','drivers.first_name as driver_name','drivers.phone_with_code as driver_phone_number','drivers.profile_picture as driver_profile_picture','booking_statuses.status_name as driver_status_name','booking_statuses.status_name_ar as driver_status_name_ar','driver_vehicles.vehicle_image','driver_vehicles.color as vehicle_color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trips.total as collection_amount')->first();
                    
        if($result->status != 5){
            $new_status = BookingStatus::where('id',$result->status+1)->first();
        }else{
            $new_status = BookingStatus::where('id',$result->status)->first();
        }
        
        $result->new_driver_status_name = $new_status->status_name;
        $result->new_driver_status_name_ar = $new_status->status_name_ar;
        $result->new_status = $new_status->id;
        $result->payment_method = DB::table('payment_methods')->where('id',$result->payment_method)->value('payment');
        
        return response()->json([
            "result"=>$result,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_ongoing_trip_details_shared(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $pending_trip = DB::table('trips')->where('driver_id',$input['driver_id'])->where('status',4)->value('id');
        if($pending_trip){
            $result = $this->get_shared_trip_data($pending_trip);
            $data['cancellation_reasons'] = DB::table('cancellation_reasons')->where('type',2)->get();
            $data['trip'] = $result;
            return response()->json([
                "result"=>$data,
                "message" => 'Success',
                "status" => 1
            ]);
        }
        $trip_d = $this->get_shared_current_trip($input['driver_id'],$input['lat'],$input['lng']);

        if($trip_d['trip_id'] == 0){
            return response()->json([
                "message" => 'Sorry no trips available',
                "status" => 0
            ]);
        }else{
            $result = $this->get_shared_trip_data($trip_d['trip_id']);
            $result->mode = $trip_d['trip_mode'];
            $data['cancellation_reasons'] = DB::table('cancellation_reasons')->where('type',2)->get();
            $data['trip'] = $result;
            return response()->json([
                "result"=>$data,
                "message" => 'Success',
                "status" => 1
            ]);
        }
    }
    
    public function get_shared_trip_data($id){
        $result = DB::table('trips')
                    ->join('customers','customers.id','=','trips.customer_id')
                    ->join('drivers','drivers.id','=','trips.driver_id')
                    ->join('booking_statuses','booking_statuses.id','=','trips.status')
                    ->join('driver_vehicles','driver_vehicles.driver_id','=','trips.driver_id')
                    ->where('trips.id',$id)
                    ->select('trips.*','customers.first_name as customer_name','customers.phone_with_code as customer_phone_number','customers.profile_picture as customer_profile_picture','booking_statuses.customer_status_name','booking_statuses.customer_status_name_ar','drivers.first_name as driver_name','drivers.phone_with_code as driver_phone_number','drivers.profile_picture as driver_profile_picture','booking_statuses.status_name as driver_status_name','booking_statuses.status_name_ar as driver_status_name_ar','driver_vehicles.vehicle_image','driver_vehicles.color as vehicle_color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trips.total as collection_amount')->first();
                    
        if($result->status != 5){
            $new_status = BookingStatus::where('id',$result->status+1)->first();
        }else{
            $new_status = BookingStatus::where('id',$result->status)->first();
        }
        
        $result->new_status = BookingStatus::where('id',$result->status+1)->first();
        $result->payment_method = DB::table('payment_methods')->where('id',$result->payment_method)->value('payment');
        $result->vehicle_slug = DB::table('vehicle_categories')->join('vehicle_slugs','vehicle_slugs.id','vehicle_categories.vehicle_slug')->where('vehicle_categories.id',$result->vehicle_type)->value('vehicle_slugs.slug');
        $result->trip_type_name = DB::table('trip_types')->where('id',$result->trip_type)->value('name');
        $result->customer = DB::table('customers')->where('id',$result->customer_id)->first();
        $result->vehicle = DB::table('driver_vehicles')->where('driver_id',$result->driver_id)->first();
        return $result;
    }
    
    public function add_missed_trip_request($customer_id,$latitude,$longitude,$zone){
        $data['customer_id'] = $customer_id;
        $data['zone'] = $zone;
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['weight'] = 0.25;
        MissedTripRequest::create($data);
        return true;
    }
    
    public function get_hiring_drivers_list(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'zone_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $drivers = DB::table('drivers')->join('zones','zones.id','=','drivers.zone')->where('drivers.zone',$input['zone_id'])->where('drivers.driver_hiring_status',1)->where('drivers.status',1)->select('drivers.*','zones.name as zone_name')->get();
        }else{
            $drivers = DB::table('drivers')->join('zones','zones.id','=','drivers.zone')->where('drivers.zone',$input['zone_id'])->where('drivers.driver_hiring_status',1)->where('drivers.status',1)->select('drivers.*','zones.name_ar as zone_name')->get();
        }
        return response()->json([
            "result"=> $drivers,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function driver_hiring_request(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'driver_id' => 'required',
            'pickup_location' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'drop_location' => 'required',
            'drop_lat' => 'required',
            'drop_lng' => 'required',
            'zone' => 'required',
            'country_id' => 'required',
            'payment_method' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $input['pickup_date'] = date("Y-m-d H:i:s", strtotime($input['pickup_date']));
        $input['drop_date'] = date("Y-m-d H:i:s", strtotime($input['drop_date']));
        $input['status'] = 1;
        
        $id = DriverHiringRequest::create($input)->id;
        $booking = DriverHiringRequest::where('id',$id)->first();
    
        return response()->json([
            "result" => $booking,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_customer_hire_bookings(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data = DB::table('driver_hiring_requests')
                    ->leftJoin('customers','customers.id','driver_hiring_requests.customer_id')
                    ->leftJoin('drivers','drivers.id','driver_hiring_requests.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','driver_hiring_requests.payment_method')
                    ->leftJoin('driver_hiring_statuses','driver_hiring_statuses.id','driver_hiring_requests.status')
                    ->select('driver_hiring_requests.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment','driver_hiring_statuses.status_name')
                    ->where('driver_hiring_requests.customer_id',$input['customer_id'])->orderBy('id', 'DESC')
                    ->get();
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_driver_hire_bookings(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data = DB::table('driver_hiring_requests')
                ->leftJoin('customers','customers.id','driver_hiring_requests.customer_id')
                ->leftJoin('drivers','drivers.id','driver_hiring_requests.driver_id')
                ->leftJoin('payment_methods','payment_methods.id','driver_hiring_requests.payment_method')
                ->leftJoin('driver_hiring_statuses','driver_hiring_statuses.id','driver_hiring_requests.status')
                ->select('driver_hiring_requests.*','customers.first_name as customer_name','drivers.first_name as driver_name','customers.profile_picture','payment_methods.payment','driver_hiring_statuses.status_name')
                ->where('driver_hiring_requests.driver_id',$input['driver_id'])->orderBy('id', 'DESC')
                ->get();
                
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function hiring_status_change(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'booking_id' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        $booking = DriverHiringRequest::where('id',$input['booking_id'])->first();
        
        DriverHiringRequest::where('id',$input['booking_id'])->update([ 'status' => $input['status']]);
        
        if($input['status'] == 5){
            DriverHiringRequest::where('id',$input['booking_id'])->update([ 'drop_date' => date('Y-m-d'), 'drop_time' => date('H:i:s') ]);
            $this->fare_calculation($booking->id);
        }   
    
        $newPost = $database
        ->getReference('hire_bookings/'.$input['booking_id'])
        ->update([
            'status' => $input['status']
        ]);
        
        $fcm_token = Customer::where('id',$booking->customer_id)->value('fcm_token');
        $current_status = DB::table('driver_hiring_statuses')->where('id',$input['status'])->first();
        if($fcm_token != "" && $fcm_token != "123456"){
            $this->send_fcm('Booking Notification',$current_status->customer_status_name,$fcm_token);
        }
        
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function fare_calculation($booking_id){
        $booking = DB::table('driver_hiring_requests')->where('id',$booking_id)->first();
        $data = DB::table('driver_hiring_fare_management')->first();
        
        $from_time = strtotime($booking->pickup_date.' '.$booking->pickup_time);
        $to_time = strtotime($booking->drop_date.' '.$booking->drop_time);
        $minutes = round(abs($to_time - $from_time) / 60);
        $hour = ceil($minutes/60);
        
        if($hour <= $data->base_hours){
            $sub_total = $data->base_fare;
        }else{
            $extra_hour = $hour - $data->base_hours;
            $sub_total = $data->base_fare + ($extra_hour * $data->extra_hour_charge);
        }
        
        //Tax
        $taxes = DB::table('tax_lists')->get();
        $total_tax = 0.00;
        if(count($taxes)){
            foreach($taxes as $key => $value){
                $total_tax = $total_tax + ($value->percent / 100) * $sub_total;
            }
        }
        $tax = number_format((float)$total_tax, 2, '.', '');
        $total_fare = $tax + $sub_total;
        $total_fare = number_format((float)$total_fare, 2, '.', '');
        
        DriverHiringRequest::where('id',$booking->id)->update([ 'sub_total' => $sub_total, 'tax' => $tax, 'total' => $total_fare ]);
        
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function hiring_accept_reject(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'booking_id' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        if($input['status'] == 2){
            $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
            $database = $factory->createDatabase();
        
            $newPost = $database
            ->getReference('hire_bookings/'.$input['booking_id'])
            ->update([
                'status' => $input['status']
            ]);   
        }
        
        DriverHiringRequest::where('id',$input['booking_id'])->update([ 'status' => $input['status'] ]);
    
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function hiring_booking_details(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'booking_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data = DriverHiringRequest::where('id',$input['booking_id'])->first();
      	$data['customer_number'] = Customer::where('id',$data->customer_id)->value('phone_with_code');
      	$data['driver_number'] = Driver::where('id',$data->driver_id)->value('phone_with_code');
      	$data['current_status_name'] = DriverHiringStatus::where('id',$data->status)->value('status_name');
        
        if($data->status < 6){
            $new = DB::table('driver_hiring_statuses')->where('id',$data->status+1)->first();
            $data->new_status_label = $new->status_name;
            $data->new_status = $new->id;
        }else{
            $new = DB::table('driver_hiring_statuses')->where('id',$data->status)->first();
            $data->new_status_label = $new->status_name;
            $data->new_status = $new->id;
        }
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    //v4
    public function get_estimation_fare(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_type' => 'required',
            'promo' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'drop_lat' => 'required',
            'drop_lat' => 'required',
            'customer_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        if($input['trip_type'] == 4){
            $vehicle_mode = 19;
        }else{
            $vehicle_mode = 18;
        }
    
        if($input['trip_type'] != 2){
            $distance = $this->get_driving_distance($input['pickup_lat'],$input['drop_lat'],$input['pickup_lng'],$input['drop_lng']);
            $input['distance'] = $distance['distance'];
        }
        if($input['lang'] == 'en'){
            $vehicles = VehicleCategory::select('id','vehicle_type','active_icon','inactive_icon','description','status')->where('vehicle_mode',$vehicle_mode)->get();
        }else{
            $vehicles = VehicleCategory::select('id','vehicle_type_ar as vehicle_type','base_fare','price_per_km','active_icon','inactive_icon','description_ar as description','status')->where('vehicle_mode',$vehicle_mode)->get();
        }
    
        $wallet_balance = Customer::where('id',$input['customer_id'])->value('wallet');
        
        foreach($vehicles as $key => $value){
            $param = $input;
            $param['vehicle_type'] = $value->id;
            $vehicles[$key]->fares = $this->get_fare($param);
        }
        $data['vehicles'] = $vehicles;
        $data['wallet'] = $wallet_balance;
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_fare($input){
        if($input['trip_type'] == 1){
            $fares = $this->calculate_daily_fare($input['vehicle_type'],$input['distance'],$input['promo']);
        }else if($input['trip_type'] == 2){
            $fares = $this->calculate_rental_fare($input['vehicle_type'],$input['package_id'],$input['promo'],0,0);
        }else if($input['trip_type'] == 3){
            $fares = $this->calculate_outstation_fare($input['vehicle_type'],$input['distance'],$input['promo'],$input['days'],$input['trip_sub_type']);
        }else if($input['trip_type'] == 4){
            $fares = $this->calculate_delivery_fare($input['vehicle_type'],$input['distance'],$input['promo'],$input['days'],$input['trip_sub_type']);
        }else if($input['trip_type'] == 5){
            $fares = $this->calculate_shared_fare($input['vehicle_type'],$input['distance'],$input['promo']);
        }
        return $fares;
    }
    
    public function get_driving_distance($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=".env('MAP_KEY');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
        
        $data['distance_with_mes'] = str_replace(",",".",$dist);
        $data['distance'] = substr($data['distance_with_mes'], 0, strlen($dist) - 3);
        $data['time'] = $time;
        
        return $data;
    }
    
    public function calculate_daily_fare($vehicle_type,$km,$promo){
        
        $data = [];
        $vehicle = DB::table('daily_fare_management')->where('vehicle_type',$vehicle_type)->first();
        
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $additional_fare = number_format((float)$data['km'],2,'.','') * number_format((float)$data['price_per_km'],2,'.','');
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('status',1)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        return $data;
    }
    
    public function calculate_rental_fare($vehicle_type,$package_id,$promo,$extra_km,$extra_hour){
        
        $data = [];
        $package_price = DB::table('rental_fare_management')->where('package_id',$package_id)->first();
        
        if(is_object($package_price)){
            $data['price_per_km'] = number_format((float)$package_price->price_per_km, 2, '.', '');
            $data['price_per_hour'] = number_format((float)$package_price->price_per_hour, 2, '.', '');
            $data['base_fare'] = number_format((float)$package_price->package_price, 2, '.', '');
            $additional_km_fare = $extra_km * $data['price_per_km'];
            $data['additional_km_fare'] = number_format((float)$additional_km_fare, 2, '.', '');
            $additional_hour_fare = $extra_hour * $data['price_per_hour'];
            $data['additional_hour_fare'] = number_format((float)$additional_hour_fare, 2, '.', '');
            
            $data['price_per_hour'] = number_format((float)$package_price->price_per_hour, 2, '.', '');
            $fare = $data['additional_km_fare'] + $data['additional_hour_fare'] + $data['base_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            $data['km'] = 0;
            
            //Tax
            $taxes = DB::table('tax_lists')->where('status',1)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        return $data;
    }
    
    public function calculate_outstation_fare($vehicle_type,$km,$promo,$days,$trip_sub_type){
        
        $data = [];
        $vehicle = DB::table('outstation_fare_management')->where('vehicle_type',$vehicle_type)->where('trip_sub_type_id',$trip_sub_type)->first();
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $data['driver_allowance'] = number_format((float)$vehicle->driver_allowance, 2, '.', '');
            $data['driver_allowance'] = $data['driver_allowance'] * $days;
            $additional_fare = number_format($data['km']) * $data['price_per_km'];
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'] + $data['driver_allowance'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('status',1)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        return $data;
    }
    
    public function calculate_delivery_fare($vehicle_type,$km,$promo,$days,$trip_sub_type){
        
        $data = [];
        $vehicle = DB::table('delivery_fare_management')->where('vehicle_type',$vehicle_type)->where('trip_sub_type_id',$trip_sub_type)->first();
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $data['driver_allowance'] = number_format((float)$vehicle->driver_allowance, 2, '.', '');
            $data['driver_allowance'] = $data['driver_allowance'] * $days;
            $additional_fare = number_format($data['km']) * $data['price_per_km'];
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'] + $data['driver_allowance'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('status',1)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        return $data;
    }
    
    public function calculate_shared_fare($vehicle_type,$km,$promo){
        
        $data = [];
        $vehicle = DB::table('shared_fare_management')->where('vehicle_type',$vehicle_type)->first();
        
        if(is_object($vehicle)){
            $data['base_fare'] = number_format((float)$vehicle->base_fare, 2, '.', '');
            $data['km'] = $km;
            $data['price_per_km'] = number_format((float)$vehicle->price_per_km, 2, '.', '');
            $additional_fare = number_format((float)$data['km'],2,'.','') * number_format((float)$data['price_per_km'],2,'.','');
            $data['additional_fare'] = number_format((float)$additional_fare, 2, '.', '');
            $fare =  $data['base_fare'] + $data['additional_fare'];
            $data['fare'] = number_format((float)$fare, 2, '.', '');
            
            //Tax
            $taxes = DB::table('tax_lists')->where('status',1)->get();
            $total_tax = 0.00;
            if(count($taxes)){
                foreach($taxes as $key => $value){
                    $total_tax = $total_tax + ($value->percent / 100) * $data['fare'];
                }
            }
            $data['tax'] = number_format((float)$total_tax, 2, '.', '');
            $total_fare = $data['tax'] + $data['fare'];
            $data['total_fare'] = number_format((float)$total_fare, 2, '.', '');
        }
        
        if($promo == 0){
            $data['discount'] = 0.00;
        }else{
            //Calculate discount
            $dd = $this->apply_discount($promo,$data['total_fare']);
            $data['discount'] = $dd['discount'];
            $data['total_fare'] = $dd['total_fare'];
        }
        
        return $data;
    }
    
    public function check_surge(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'zone' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $surge_settings = DB::table('surge_settings')->first();
        $date = new DateTime;
        $date->modify('-'.$surge_settings->searching_time.' seconds');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $data = DB::table('missed_trip_requests')->where('created_at','>=',$formatted_date)->where('zone',$input['zone'])->select('latitude','longitude','weight')->get();
        $count = 0;
        foreach($data as $key => $value){
            $distance = $this->distance($input['latitude'], $input['longitude'], $value->latitude, $value->longitude, 'K') ;
            if($distance <= 1){
                $count = $count + 1;
            }
        }
        if($count >= $surge_settings->minimum_trips){
            $result = DB::table('surge_fare_settings')->where('requests','>=',$count)->where('requests','<=', $count)->value('surge');
        }else{
            $result = 1;
        }
        return response()->json([
            "result" => $result,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function customer_bookings(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'lang' => 'required',
            'filter' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            if($input['filter'] == 1){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type')
                    ->where('trips.customer_id',$input['customer_id'])
                    ->whereIn('trips.status',[1,2,3,4])->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 2){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type')
                    ->where('trips.customer_id',$input['customer_id'])
                    ->where('trips.status',5)->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 3){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type')
                    ->where('trips.customer_id',$input['customer_id'])
                    ->whereIn('trips.status',[6,7])->orderBy('id', 'DESC')
                    ->get();
            }
            
        }else{
            if($input['filter'] == 1){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment_ar as payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name_ar as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type_ar as vehicle_type')
                    ->where('trips.customer_id',$input['customer_id'])
                    ->whereIn('trips.status',[1,2,3,4])->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 2){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment_ar as payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name_ar as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type_ar as vehicle_type')
                    ->where('trips.customer_id',$input['customer_id'])
                    ->where('trips.status',5)->orderBy('id', 'DESC')
                    ->get();
            }else if($input['filter'] == 3){
                $data = DB::table('trips')
                    ->leftJoin('customers','customers.id','trips.customer_id')
                    ->leftJoin('drivers','drivers.id','trips.driver_id')
                    ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                    ->leftJoin('trip_types','trip_types.id','trips.trip_type')
                    ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                    ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                    ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                    ->select('trips.*','customers.first_name as customer_name','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment_ar as payment','driver_vehicles.brand','driver_vehicles.color','driver_vehicles.vehicle_name','driver_vehicles.vehicle_number','trip_types.name_ar as trip_type','booking_statuses.status_name','vehicle_categories.vehicle_type_ar as vehicle_type')
                    ->where('trips.customer_id',$input['customer_id'])
                    ->whereIn('trips.status',[6,7])->orderBy('id', 'DESC')
                    ->get();
            }
            
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_tips(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $result['data'] = DB::table('tips')->pluck('tip');
        $result['tip'] = DB::table('trips')->where('id',$input['trip_id'])->value('tip');
        return response()->json([
            "result"=>$result,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_tip(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'tip' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = DB::table('trips')->where('id',$input['trip_id'])->update([ "tip" => $input['tip']]);
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_heatmap_coordinates(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'zone' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $surge_settings = DB::table('surge_settings')->first();
        
        $date = new DateTime;
        $date->modify('-'.$surge_settings->searching_time.' seconds');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $data = MissedTripRequest::where('created_at','>=',$formatted_date)->where('zone',$input['zone'])->select('latitude','longitude','weight')->get();
        if(count($data) >= $surge_settings->minimum_trips){
            $result = $data;            
        }else{
            $result = [];
        }
        return response()->json([
            "result" => $result,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function ride_confirm(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'km' => 'required',
            'vehicle_type' => 'required',
            'customer_id' => 'required',
            'pickup_address' => 'required',
            'pickup_date' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'trip_type' => 'required',
            'zone' => 'required',
            'surge' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $stops = json_decode($input['stops']);
        $input['pickup_date'] = date("Y-m-d H:i:s", strtotime($input['pickup_date']));
        $current_date = $this->get_date();
        
            $interval_time = $this->date_difference($input['pickup_date'],$current_date);
            if($interval_time <= 30){
                $input['booking_type'] = 1;
                $input['status'] = 1;
            }else{
                $input['booking_type'] = 2;
                $input['status'] = 2;
            }
        
        
        if($stops){
            $input['is_multiple_drops'] = 1;
        }else{
            $input['is_multiple_drops'] = 0;
        }
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        $drivers = $database->getReference('drivers/'.$input['vehicle_type'])
                    ->getSnapshot()->getValue();
        $min_earning = 0;
        $min_driver_id = 0;
        $booking_searching_radius = TripSetting::value('booking_searching_radius');
        $shared_trip_settings = DB::table('shared_trip_settings')->first();
        $shared_ride_mode = 0;
        $i=0;
        if($input['trip_type'] == 1 || $input['trip_type'] == 2 || $input['trip_sub_type'] == 5){
            $input['trip_sub_type'] = 0;
        }
        if(!$drivers){
            $this->add_missed_trip_request($input['customer_id'],$input['pickup_lat'],$input['pickup_lng'],$input['zone']);
            return response()->json([
                "message" => 'Sorry drivers not available right now',
                "status" => 0
            ]);
        }
        if($input['trip_type'] != 5){
            foreach($drivers as $key => $value){
                if($value && array_key_exists('driver_id', $value)){
                    $distance = $this->distance($input['pickup_lat'], $input['pickup_lng'], $value['geo']['lat'], $value['geo']['lng'], 'K') ;
                    $driver_wallet = Driver::where('id',$value['driver_id'])->value('wallet');
                    if($distance <= $booking_searching_radius && $value['online_status'] == 1 && $value['booking']['booking_status'] == 0 && $driver_wallet > 0){
                        $earning = $this->get_today_driver_earnings($value['driver_id']);
                        if($min_earning == 0 && $i == 0){
                            $min_earning = $earning;
                            $min_driver_id = $value['driver_id'];
                        }else if($earning < $min_earning){
                            $min_earning = $earning;
                            $min_driver_id = $value['driver_id'];
                        }
                        $i++;
                        
                    }
                }
                
            }
        }else{
            foreach($drivers as $key => $value){
                if($value && array_key_exists('driver_id', $value)){
                    $check = $this->check_shared_nearest_driver($value['driver_id'], $input['pickup_lat'], $input['pickup_lng'], $input['drop_lat'], $input['drop_lng'], $value, $booking_searching_radius, $shared_trip_settings);
                    if($check != 0 && $min_driver_id == 0){
                        $min_driver_id = $value['driver_id'];
                        $shared_ride_mode = $check;
                    }else if($check == 2){
                        $min_driver_id = $value['driver_id'];
                        $shared_ride_mode = $check;
                    }
                }
                
            }
        }
        if($min_driver_id == 0 && $input['booking_type'] == 1){
            $this->add_missed_trip_request($input['customer_id'],$input['pickup_lat'],$input['pickup_lng'],$input['zone']);
            return response()->json([
                "message" => 'Sorry drivers not available right now',
                "status" => 0
            ]);
        }
        $url = 'https://maps.googleapis.com/maps/api/staticmap?center='.$input['pickup_lat'].','.$input['pickup_lng'].'&zoom=16&size=600x300&maptype=roadmap&markers=color:red%7Clabel:L%7C'.$input['pickup_lat'].','.$input['pickup_lng'].'&key='.env('MAP_KEY');
            $img = 'trip_request_static_map/'.md5(time()).'.png';
        file_put_contents('uploads/'.$img, file_get_contents($url));
        
        $tt_name = '';
        if($input['trip_type'] == 1){
            $fares = $this->calculate_daily_fare($input['vehicle_type'],$input['km'],$input['promo'],$input['surge']);
        }
        
        if($input['trip_type'] == 2){
            $fares = $this->calculate_rental_fare($input['vehicle_type'],$input['package_id'],$input['promo'],0,0,$input['surge']);
        }
        
        if($input['trip_type'] == 3){
            $fares = $this->calculate_outstation_fare($input['vehicle_type'],$input['km'],$input['promo'],1,$input['trip_sub_type'],$input['surge']);
        }
        
        if($input['trip_type'] == 4){
            $fares = $this->calculate_delivery_fare($input['vehicle_type'],$input['km'],$input['promo'],1,$input['trip_sub_type'],$input['surge']);
        }
        
        if($input['trip_type'] == 5){
            $tt_name = "Shared";
            $fares = $this->calculate_shared_fare($input['vehicle_type'],$input['km'],$input['promo'],$input['surge']);
        }
        
        unset($input['stops']);
        $booking_request = $input;
        $booking_request['distance'] = $input['km'];
        unset($booking_request['km']);
        $booking_request['total'] = $fares['total_fare'];
        $booking_request['sub_total'] = $fares['fare'];
        $booking_request['discount'] = $fares['discount'];
        $booking_request['tax'] = $fares['tax'];
        $booking_request['static_map'] = $img;
        $booking_request['driver_id'] = $min_driver_id;
        $id = TripRequest::create($booking_request)->id;
        
        if($stops){
            foreach($stops as $key => $value){
                Stop::create([
                   "trip_request_id" =>  $id,
                   "trip_id" => 0,
                   "address" => $value->address,
                   "latitude" => $value->lat,
                   "longitude" => $value->lng
                ]);    
            }
        }
            
        if($input['booking_type'] == 2){
            return response()->json([
                "result" => $id,
                "booking_type" => $input['booking_type'],
                "message" => 'Success',
                "status" => 1
            ]);
        }
        
        $newPost = $database
        ->getReference('customers/'.$input['customer_id'])
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
            'is_searching' => 1
        ]);
        if($input['trip_type'] == 2){
            $input['drop_address'] = "Sorry, customer not mentioned";
        }
        
        if($input['trip_type'] == 5 && $shared_ride_mode == 2){
            $newPost = $database
            ->getReference('shared/'.$min_driver_id)
            ->update([
                'booking_id' => $id,
                'pickup_address' => $input['pickup_address'],
                'drop_address' => $input['drop_address'],
                'total' => $fares['total_fare'],
                'customer_name' => Customer::where('id',$input['customer_id'])->value('first_name'),
            ]);
        }else{
            $newPost = $database
            ->getReference('drivers/'.$input['vehicle_type'].'/'.$min_driver_id.'/booking')
            ->update([
                'booking_id' => $id,
                'booking_status' => 1,
                'trip_type' => $tt_name
            ]);
        }
        return response()->json([
            "result" => $id,
            "booking_type" => $input['booking_type'],
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function trip_accept(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $trip = TripRequest::where('id',$input['trip_id'])->first()->toArray();
        if($trip['booking_type'] == 1 && $trip['status'] != 1){
                return response()->json([
                "message" => 'Sorry booking not available',
                "status" => 0
            ]);
        }
        $customer_id = TripRequest::where('id',$input['trip_id'])->value('customer_id');
        $phone_with_code = Customer::where('id',$customer_id)->value('phone_with_code');
        $data = $trip;
        $data['driver_id'] = $input['driver_id'];
        $data['promo_code'] = $data['promo'];
        unset($data['promo']);
        unset($data['id']);
        $data['pickup_date'] = $trip['pickup_date'];
        $data['package_id'] = $trip['package_id'];
        $data['trip_type'] = $trip['trip_type'];
        $data['surge'] = $trip['surge'];
        $data['static_map'] = $trip['static_map'];
        $data['trip_sub_type'] = $trip['trip_sub_type'];
        $data['is_multiple_drops'] = $trip['is_multiple_drops'];
        $data['status'] = 1;
        $data['vehicle_type'] = $trip['vehicle_type'];
        $data['vehicle_id'] = DB::table('driver_vehicles')->where('driver_id',$input['driver_id'])->value('id');
        $data['otp'] = $otp = rand(1000,9999);
        //$message = "Hi ".env('APP_NAME')." , Your OTP code is:  ".$data['otp'];
            //$this->sendSms($phone_with_code,$message);
        $id = Trip::create($data)->id;
        Stop::where('trip_request_id',$input['trip_id'])->update([
            "trip_id" => $id 
        ]);
        $trip_id = str_pad($id,6,"0",STR_PAD_LEFT);
        Trip::where('id',$id)->update([ 'trip_id' => $trip_id ]);
        
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();

        $trip_details = Trip::where('id',$id)->first();
        $customer = Customer::where('id',$trip_details->customer_id)->first();
        $driver = Driver::where('id',$trip_details->driver_id)->first();
        $vehicle = DriverVehicle::where('id',$trip_details->vehicle_id)->first();
        $current_status = BookingStatus::where('id',1)->first();
        $new_status = BookingStatus::where('id',2)->first();
        $payment_method = PaymentMethod::where('id',$trip_details->payment_method)->value('Payment');
        
        $data['driver_id'] = $input['driver_id'];
        $data['trip_request_id'] = $input['trip_id'];
        $data['status'] = 3;
        DriverTripRequest::create($data);
        
        //Trip Firebase 
        /*$data = [];
        $data['id'] = $id;
        $data['trip_id'] = $trip_id;
        $data['trip_type'] = $trip_details->trip_type;
        $data['vehicle_type'] = $trip_details->vehicle_type;
        $data['customer_id'] = $trip_details->customer_id;
        $data['customer_name'] = $customer->first_name;
        $data['customer_profile_picture'] = $customer->profile_picture;
        $data['customer_phone_number'] = $customer->phone_number;
        $data['driver_id'] = $trip_details->driver_id;
        $data['driver_name'] = $driver->first_name;
        $data['driver_profile_picture'] = $driver->profile_picture;
        $data['is_multiple_drops'] = $trip_details->is_multiple_drops;
        $data['driver_phone_number'] = $driver->phone_number;
        $data['pickup_address'] = $trip_details->pickup_address;
        $data['pickup_lat'] = $trip_details->pickup_lat;
        $data['pickup_lng'] = $trip_details->pickup_lng;
        $data['drop_address'] = $trip_details->drop_address;
        $data['drop_lat'] = $trip_details->drop_lat;
        $data['drop_lng'] = $trip_details->drop_lng;
        $data['payment_method'] = $payment_method;
        $data['surge'] = $trip_details->surge;
        $data['pickup_date'] = $trip_details->pickup_date;
        $data['total'] = $trip_details->total;
        $data['collection_amount'] = $trip_details->total;
        $data['vehicle_id'] = $trip_details->vehicle_id;
        $data['otp'] = $trip_details->otp;
        $data['vehicle_image'] = $vehicle->vehicle_image;
        $data['vehicle_number'] = $vehicle->vehicle_number;
        $data['vehicle_color'] = $vehicle->color;
        $data['vehicle_name'] = $vehicle->vehicle_name;
        $data['customer_status_name'] = $current_status->customer_status_name;
        $data['customer_status_name_ar'] = $current_status->customer_status_name_ar;
        $data['driver_status_name'] = $current_status->status_name;
        $data['driver_status_name_ar'] = $current_status->status_name_ar;
        $data['status'] = 1;
        $data['new_driver_status_name'] = $new_status->status_name;
        $data['new_driver_status_name_ar'] = $new_status->status_name_ar;
        $data['new_status'] = 2;
        $data['driver_lat'] = 0;
        $data['driver_lng'] = 0;
        $data['bearing'] = 0;*/
        
        $newPost = $database
        ->getReference('trips/'.$trip_details->id)
        ->update(['status' => $trip_details->status]);
        $newPost = $database
        ->getReference('customers/'.$trip['customer_id'])
        ->update([
            'booking_id' => $id,
            'booking_status' => 2,
            'is_searching' => 0
        ]);
        $shared_ride_count = DB::table('trips')->where('driver_id',$input['driver_id'])->where('trip_type',5)->where('status','<',5)->where('id','!=',$id)->count();
        
        if($trip_details->trip_type == 5 && $shared_ride_count > 0){
            $newPost = $database
            ->getReference('shared/'.$input['driver_id'])
            ->update([
                'booking_id' => 0,
                'pickup_address' => '',
                'drop_address' => '',
                'total' => 0,
                'customer_name' => '',
            ]);
        }else{
           $newPost = $database
            ->getReference('drivers/'.$trip['vehicle_type'].'/'.$input['driver_id'].'/booking')
            ->update([
                'booking_id' => $id,
                'booking_status' => 2
            ]); 
        }
        
        TripRequest::where('id',$input['trip_id'])->update([ 'status' => 3 ]);
        if($customer->fcm_token != "" && $customer->fcm_token != "123456"){
            $this->send_fcm($current_status->status_name,$current_status->customer_status_name,$customer->fcm_token);
        }
        
        return response()->json([
            "result" => $id,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function trip_reject(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'driver_id' => 'required',
            'from' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $trip = TripRequest::where('id',$input['trip_id'])->first();
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        if($input['from'] == 1){
            $newPost = $database
            ->getReference('drivers/'.$trip->vehicle_type.'/'.$input['driver_id'].'/booking')
            ->update([
                'booking_id' => 0,
                'booking_status' => 0,
            ]);
        }else{
            $newPost = $database
            ->getReference('/shared/'.$input['driver_id'])
            ->update([
                'booking_id' => 0,
                'pickup_address' => "",
                'drop_address' => "",
                'total' => 0,
                'customer_name' => "",
            ]);
        }
        
        $data['driver_id'] = $input['driver_id'];
        $data['trip_request_id'] = $input['trip_id'];
        $data['status'] = 4;
        
        DriverTripRequest::create($data);
        if($trip->booking_type == 1 && $trip->status == 1){
            TripRequest::where('id',$input['trip_id'])->update([ 'status' => 4 ]);
            $this->find_driver($input['trip_id']);
        }
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function trip_request_details(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_request_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data = DB::table('trip_requests')
                ->join('customers','customers.id','=','trip_requests.customer_id')
                ->join('trip_types','trip_types.id','=','trip_requests.trip_type')
                ->where('trip_requests.id',$input['trip_request_id'])
                ->select('trip_requests.*','customers.first_name','trip_types.name as trip_type_name')
                ->first();
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }    
    
    public function customer_trip_details(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $trip = DB::table('trips')->where('id',$input['trip_id'])->first();
        $driver = DB::table('drivers')->where('id',$trip->driver_id)->first();
        $trip->vehicle_slug = DB::table('vehicle_categories')->join('vehicle_slugs','vehicle_slugs.id','vehicle_categories.vehicle_slug')->where('vehicle_categories.id',$trip->vehicle_type)->value('vehicle_slugs.slug');
        $trip->trip_type_name = DB::table('trip_types')->where('id',$trip->trip_type)->value('name');
        $trip->status_name = DB::table('booking_statuses')->where('id',$trip->status)->value('status_name');
        $trip->driver = DB::table('drivers')->where('id',$trip->driver_id)->first();
        $trip->vehicle = DB::table('driver_vehicles')->where('driver_id',$trip->driver_id)->first();
        $data['trip'] = $trip;
        $data['cancellation_reasons'] = DB::table('cancellation_reasons')->where('type',1)->get();
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function driver_trip_details(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $trip = DB::table('trips')->where('id',$input['trip_id'])->first();
        $trip->vehicle_slug = DB::table('vehicle_categories')->join('vehicle_slugs','vehicle_slugs.id','vehicle_categories.vehicle_slug')->where('vehicle_categories.id',$trip->vehicle_type)->value('vehicle_slugs.slug');
        $trip->trip_type_name = DB::table('trip_types')->where('id',$trip->trip_type)->value('name');
        $trip->customer = DB::table('customers')->where('id',$trip->customer_id)->first();
        $trip->vehicle = DB::table('driver_vehicles')->where('driver_id',$trip->driver_id)->first();
        $trip->new_status = BookingStatus::where('id',$trip->status+1)->first();
        if($trip->trip_type == 2){
            $trip->package_details = DB::table('packages')->where('id',$trip->package_id)->first();
        }
        $data['trip'] = $trip;
        $data['cancellation_reasons'] = DB::table('cancellation_reasons')->where('type',2)->get();
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function promo_codes(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = [];
            $codes = PromoCode::select('id','promo_name','promo_code','description','promo_type','discount','redemptions','min_fare','max_discount_value','status')->where('customer_id',$input['customer_id'])->orWhere('customer_id',0)->get();
            
            foreach($codes as $key => $value){
                $using_count = CustomerPromoHistory::where('customer_id',$input['customer_id'])->where('promo_id',$value->id)->where('status',1)->count();
                if($value->redemptions > $using_count){
                    array_push($data,$value);
                }
            }
        }else{
            $data = [];
            $codes = PromoCode::select('id','promo_name_ar as promo_name','promo_code_ar as promo_code','description_ar as description','promo_type','discount','redemptions','min_fare','max_discount_value','status')->where('customer_id',$input['customer_id'])->orWhere('customer_id',0)->get();
            foreach($codes as $key => $value){
                $using_count = CustomerPromoHistory::where('customer_id',$input['customer_id'])->where('promo_id',$value->id)->where('status',1)->count();
                if($value->redemptions > $using_count){
                    array_push($data,$value);
                }
            }
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function change_trip_status(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        Trip::where('id',$input['trip_id'])->update([ 'status' => $input['status']]);
        
        if($input['status'] == 3){
            Trip::where('id',$input['trip_id'])->update([ 'start_time' => date('Y-m-d H:i:s'), 'actual_pickup_address' => $input['address'], 'actual_pickup_lat' => $input['lat'], 'actual_pickup_lng' => $input['lng'] ]);
        }
        
        $trip = Trip::where('id',$input['trip_id'])->first();
        
        if($input['status'] == 4){
            Trip::where('id',$input['trip_id'])->update([ 'end_time' => date('Y-m-d H:i:s'),'actual_drop_address' => $input['address'], 'actual_drop_lat' => $input['lat'], 'actual_drop_lng' => $input['lng'] ]);
            
            $this->calculate_fare($input['trip_id']);
            $newPost = $database
            ->getReference('customers/'.$trip->customer_id)
            ->update([
                'booking_id' => 0,
                'booking_status' => 0,
                'is_searching' => 0
            ]);
            $vehicle_type = DriverVehicle::where('id',$trip->vehicle_id)->value('vehicle_type');
            $newPost = $database
            ->getReference('drivers/'.$vehicle_type.'/'.$trip->driver_id.'/booking')
            ->update([
                'booking_id' => 0,
                'booking_status' => 0,
            ]);
           $this->get_shared_current_trip($trip->driver_id,$input['lat'],$input['lng']);
        }
        
        if($input['status'] != 5){
            $current_status = BookingStatus::where('id',$input['status'])->first();
        }else{
            $current_status = BookingStatus::where('id',$input['status'])->first();
            $this->calculate_earnings($input['trip_id']);
        }
        
        $fcm_token = Customer::where('id',$trip->customer_id)->value('fcm_token');
        if($fcm_token != "" && $fcm_token != "123456"){
            $this->send_fcm($current_status->status_name,$current_status->customer_status_name,$fcm_token);
        }
        
        $newPost = $database
        ->getReference('trips/'.$input['trip_id'])
        ->update([
            'status' => $current_status->id,
        ]);
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_shared_current_trip($driver_id,$lat,$lng){
        $trips = DB::table('trips')->select('id','pickup_lat','pickup_lng','drop_lat','drop_lng','status')->where('driver_id',$driver_id)->where('status','<',4)->get();
        //$trips = DB::table('trips')->select('id','pickup_lat','pickup_lng','drop_lat','drop_lng','status')->where('driver_id',$driver_id)->get();
        $min_trip_id = 0;
        $distance = 0;
        $trip_mode = "";
        if($trips->count()){
            $at_point = DB::table('trips')->where('driver_id',$driver_id)->where('status','=',2)->value('id');
            if(!$at_point){
                foreach($trips as $key => $value){
                    if($value->status < 3){
                        $p_distance = $this->distance($value->pickup_lat, $value->pickup_lng, $lat, $lng, 'K') ;
                        $d_distance = $this->distance($value->drop_lat, $value->drop_lng, $lat, $lng, 'K');
                        $l_distance = 0;
                        $mode = 0;
                        if($p_distance < $d_distance){
                            $l_distance = $p_distance;
                            $mode = 1;
                        }else{
                            $l_distance = $d_distance;
                            $mode = 2;
                        }
                    }else{
                        $l_distance = $this->distance($value->drop_lat, $value->drop_lng, $lat, $lng, 'K');
                        $mode = 2;
                    }
                    if($distance == 0 || $l_distance <= $distance){
                        $min_trip_id = $value->id;
                        $trip_mode = $mode;
                        $distance = $l_distance;
                    }
                }
                $data['trip_id'] = $min_trip_id;
                $data['trip_mode'] = $trip_mode;
                $this->update_shared_current_booking($min_trip_id);
            }else{
                $data['trip_id'] = $at_point;
                $data['trip_mode'] = 1;
                $this->update_shared_current_booking($at_point);
            }
        }else{
            $data['trip_id'] = 0;
            $data['trip_mode'] = 0;
        }
        return $data;
    }
    
    public function update_shared_current_booking($trip_id){
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        $trip = DB::table('trips')->where('id',$trip_id)->first();
        $customer_name = DB::table('customers')->where('id',$trip->customer_id)->value('first_name');
        $trip_type = DB::table('trip_types')->where('id',$trip->trip_type)->first();
        $newPost = $database
            ->getReference('drivers/'.$trip->vehicle_type.'/'.$trip->driver_id.'/booking')
            ->update([
                'booking_id' => $trip->id,
                'booking_status' => 2,
            ]);
    }
    
    public function calculate_fare($trip_id){
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        $trip = Trip::where('id',$trip_id)->first();
        $distance = $this->get_distance($trip_id);
        if($distance != 0 && is_object($trip)){
            if($trip->trip_type == 1){
                $fare = $this->calculate_daily_fare($trip->vehicle_type,$distance,$trip->promo_code,$trip->surge);
            }else if($trip->trip_type == 2){
                $input['start_date'] = date("Y-m-d H:i:s", strtotime($trip->start_time));
                $input['end_date'] = date("Y-m-d H:i:s", strtotime($trip->end_time));
                $interval_time = $this->date_difference($input['start_date'],$input['end_date']);
                
                $hours = ceil($interval_time/60);
                
               $fare = $this->calculate_rental_fare($trip->vehicle_type,$trip->package_id,$trip->promo_code,$distance,$hours,$trip->surge);
            }else if($trip->trip_type == 3){
                $input['start_date'] = date("Y-m-d H:i:s", strtotime($trip->start_date));
                $input['end_date'] = date("Y-m-d H:i:s", strtotime($trip->end_date));
                $interval_time = $this->date_difference($input['start_date'],$input['end_date']);
                
                $days = ceil($interval_time/1440);
                
                $fare = $this->calculate_outstation_fare($trip->vehicle_type,$distance,$trip->promo_code,$days,$trip->trip_sub_type,$trip->surge);
            
            }else if($trip->trip_type == 4){
                $input['start_date'] = date("Y-m-d H:i:s", strtotime($trip->start_date));
                $input['end_date'] = date("Y-m-d H:i:s", strtotime($trip->end_date));
                $interval_time = $this->date_difference($input['start_date'],$input['end_date']);
                
                $days = ceil($interval_time/1440);
                
                $fare = $this->calculate_delivery_fare($trip->vehicle_type,$distance,$trip->promo_code,$days,$trip->trip_sub_type,$trip->surge);
            
            }else if($trip->trip_type == 5){
                $fare = $this->calculate_shared_fare($trip->vehicle_type,$distance,$trip->promo_code,$trip->surge);
            }
            
            if($trip->trip_type == 5){
                $fare['total_fare'] = $trip->total;
            }
            
            if($fare['total_fare'] > $trip->total){
                if($trip->tip){
                    $fare['total_fare'] = $fare['total_fare'] + $trip->tip;   
                }
                $collection_amount = $this->update_payment_mode($trip->id,$trip,$fare['total_fare']);
                Trip::where('id',$trip_id)->update([ 'total' => $fare['total_fare'], 'sub_total' => $fare['fare'], 'tax' => $fare['tax'], 'discount' => $fare['discount'], 'distance' => $distance, 'collection_amount' => $collection_amount]);
                $data['total'] = $fare['total_fare'];
            }else{
                if($trip->tip){
                    $trip->total = $trip->total + $trip->tip;   
                    Trip::where('id',$trip_id)->update([ 'total' => $trip->total]);
                }
                $this->check_subscription_trip($trip->id,$trip->trip_type,$trip->customer_id);
                $collection_amount = $this->update_payment_mode($trip->id,$trip,$trip->total);
                Trip::where('id',$trip_id)->update(['collection_amount' => $collection_amount]);
            }
            
            //Check promo applied or not
            if($fare['discount']){
                $this->update_promo_histories($trip->promo_code,$trip,$trip->customer_id);
            }else{
                Trip::where('id',$trip_id)->update([ "promo_code" => 0 ]);
            }
        }
    }
    
    public function get_distance($trip_id){
        $trip = Trip::where('id',$trip_id)->first();
        if($trip->is_multiple_drops == 1){
            $stops = DB::table('stops')->where('trip_id',$trip_id)->get();
            $waypoints = "";
            foreach($stops as $key => $value){
                if($key == 0){
                    $waypoints .= 'via:'.$value->latitude.",".$value->longitude;
                }else{
                    $waypoints .= '|via:'.$value->latitude.",".$value->longitude;
                }
            }
            $url= 'https://maps.googleapis.com/maps/api/directions/json?origin='.$trip->actual_pickup_lat.','.$trip->actual_pickup_lng.'&destination='.$trip->actual_drop_lat.','.$trip->actual_drop_lng.'&waypoints='.$waypoints.'&key='.env('MAP_KEY');
        }else{
            $url= 'https://maps.googleapis.com/maps/api/directions/json?origin='.$trip->actual_pickup_lat.','.$trip->actual_pickup_lng.'&destination='.$trip->actual_drop_lat.','.$trip->actual_drop_lng.'&key='.env('MAP_KEY');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec ($ch);
        $err = curl_error($ch);  //if you need
        curl_close ($ch);
        $result = json_decode($response);
        if(@$result->routes[0]->legs[0]->distance->text){
            $distance = str_replace(" km","",$result->routes[0]->legs[0]->distance->text);
            $distance = str_replace(" m","",$distance);
            $distance = str_replace(" ft","",$distance);
            return $distance;
        }else{
             return 0;
        }
    }
    
    public function update_payment_mode($trip_id,$trip,$fare){
        $is_subscription_trip = Trip::where('id',$trip_id)->value('is_subscription_trip');
        if($is_subscription_trip){
            Trip::where('id',$trip_id)->update(['payment_method' => PaymentMethod::where('payment_type',4)->value('id') ]);
            $payment_history['trip_id'] = $trip_id;
            $payment_history['mode'] = "Subscription";
            $payment_history['amount'] = $fare;
            PaymentHistory::create($payment_history);
            return 0;
        }
        $customer_wallet = Customer::where('id',$trip->customer_id)->value('wallet');
        if($customer_wallet && $customer_wallet > 0){
            $remaining_fare = $customer_wallet - $fare;
            $remaining_fare = number_format((float)$remaining_fare, 2, '.', '');
            if($remaining_fare >= 0){
                Trip::where('id',$trip_id)->update(['payment_method' => 2 ]);
                Customer::where('id',$trip->customer_id)->update([ 'wallet' => $remaining_fare ]);
               
                $payment_history['trip_id'] = $trip_id;
                $payment_history['mode'] = "Wallet";
                $payment_history['amount'] = $fare;
                PaymentHistory::create($payment_history);
                CustomerWalletHistory::create(['customer_id' => $trip->customer_id, 'type' => 2, 'message' => 'Amount debited for booking(#'.$trip->trip_id.')','message_ar' => 'ام لم للحجز(#'.$trip->trip_id.')', 'amount' => $fare, 'transaction_type' => 3 ]);
                return 0;
            }else{
                Trip::where('id',$trip_id)->update(['payment_method' => 3 ]);
                Customer::where('id',$trip->customer_id)->update([ 'wallet' => 0 ]);
                $payment_history['trip_id'] = $trip_id;
                $payment_history['mode'] = "Wallet";
                $payment_history['amount'] = $customer_wallet;
                PaymentHistory::create($payment_history);
                $payment_history['trip_id'] = $trip_id;
                $payment_history['mode'] = "Cash";
                $payment_history['amount'] = abs($remaining_fare);
                PaymentHistory::create($payment_history);
                CustomerWalletHistory::create(['customer_id' => $trip->customer_id, 'type' => 2, 'message' => 'Amount debited for booking(#'.$trip->trip_id.')','message_ar' => 'المبلغ المخو حز(#'.$trip->trip_id.')', 'amount' => $customer_wallet, 'transaction_type' => 3 ]);
                return abs($remaining_fare);
            }
            
        }else{
            $payment_history['trip_id'] = $trip_id;
            $payment_history['mode'] = "Cash";
            $payment_history['amount'] = $fare;
            PaymentHistory::create($payment_history);
            return abs($fare);
        }
    }
    
    public function trip_cancel_by_customer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'status' => 'required',
            'reason_id' => 'required',
            'cancelled_by' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data['trip_id'] = $input['trip_id'];
        $data['reason_id'] = $input['reason_id'];
        $data['cancelled_by'] = $input['cancelled_by'];
        
        TripCancellation::create($data);
        
        Trip::where('id',$input['trip_id'])->update([ 'status' => $input['status']]);
        
        $trip = Trip::where('id',$input['trip_id'])->first();

        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        $newPost = $database
        ->getReference('customers/'.$trip->customer_id)
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
            'is_searching' => 0
        ]);
        
        $vehicle_type = DriverVehicle::where('id',$trip->vehicle_id)->value('vehicle_type');
        
        $newPost = $database
        ->getReference('drivers/'.$vehicle_type.'/'.$trip->driver_id.'/booking')
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
        ]);
        
        $newPost = $database
        ->getReference('trips/'.$input['trip_id'])
        ->update([
            'status' => $input['status']
        ]);
        
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function trip_cancel_by_driver(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'status' => 'required',
            'reason_id' => 'required',
            'cancelled_by' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $data['trip_id'] = $input['trip_id'];
        $data['reason_id'] = $input['reason_id'];
        $data['cancelled_by'] = $input['cancelled_by'];
        
        TripCancellation::create($data);
        
        Trip::where('id',$input['trip_id'])->update([ 'status' => $input['status']]);
        
        $current_status = BookingStatus::where('id',$input['status'])->first();
        $trip = Trip::where('id',$input['trip_id'])->first();
        $fcm_token = Customer::where('id',$trip->customer_id)->value('fcm_token');
        
        if($fcm_token != "" && $fcm_token != "123456"){
            $this->send_fcm($current_status->status_name,$current_status->customer_status_name,$fcm_token);
        }
        
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        $newPost = $database
        ->getReference('customers/'.$trip->customer_id)
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
            'is_searching' => 0
        ]);
        
        $vehicle_type = DriverVehicle::where('id',$trip->vehicle_id)->value('vehicle_type');
        
        $newPost = $database
        ->getReference('drivers/'.$vehicle_type.'/'.$trip->driver_id.'/booking')
        ->update([
            'booking_id' => 0,
            'booking_status' => 0,
        ]);
        
        $newPost = $database
        ->getReference('trips/'.$input['trip_id'])
        ->update([
            'status' => $input['status']
        ]);
        
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
        
    }
    
    public function get_bill(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $trip = DB::table('trips')->where('id',$input['trip_id'])->first();
        $trip->customer = DB::table('customers')->where('id',$trip->customer_id)->first();
        $trip->driver = DB::table('drivers')->where('id',$trip->driver_id)->first();
        $trip->trip_type_name = DB::table('trip_types')->where('id',$trip->trip_type)->value('name');
        $trip->vehicle_type = DB::table('vehicle_categories')->where('id',$trip->vehicle_type)->value('vehicle_type');
        return response()->json([
            "result" => $trip,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_payment_intent(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $stripe = Stripe::make(env('STRIPE_SECRET_KEY'));
        $customer = $stripe->customers()->create([
            'email' => $input['email'],
        ]);
        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => 20,
            'currency' => 'usd',
            'payment_method_types' => [
                'card',
            ],
        ]);
        $epkey = $stripe->EphemeralKey()->create($customer['id']);
        $data['ephemeralKey'] = $epkey['secret'];
        $data['customer'] = $customer['id'];
        $data['paymentIntent'] = $paymentIntent['client_secret'];
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);    
    }
    
    public function get_driver_payment_intent(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $stripe = Stripe::make(env('STRIPE_SECRET_KEY'));
        $driver = $stripe->drivers()->create([
            'email' => $input['email'],
        ]);
        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => 20,
            'currency' => 'usd',
            'payment_method_types' => [
                'card',
            ],
        ]);
        $epkey = $stripe->EphemeralKey()->create($driver['id']);
        $data['ephemeralKey'] = $epkey['secret'];
        $data['driver'] = $driver['id'];
        $data['paymentIntent'] = $paymentIntent['client_secret'];
        
        return response()->json([
            "result" => $data,
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
