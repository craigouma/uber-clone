<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Driver;
use App\AppSetting;
use App\ContactSetting;
use App\DriverRecharge;
use App\PaymentMethod;
use App\Models\DriverQuery;
use App\NotificationMessage;
use App\DriverEarning;
use App\Customer;
use App\DriverBankKycDetail;
use App\Country;
use App\Currency;
use App\Status;
use App\Faq;
use App\Trip;
use App\DriverTutorial;
use App\DriverVehicle;
use App\VehicleCategory;
use App\DriverWithdrawal;
use App\DriverWalletHistory;
use App\CustomerWalletHistory;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

class DriverController extends Controller
{
    //Registration Process
    
    public function get_zones(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'country_code' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $country_id = DB::table('countries')->where('phone_code',$input['country_code'])->value('id');
        
        if($country_id){
            if($input['lang'] == 'en'){
                $zones = DB::table('zones')->select('id','name')->where('country_id',$country_id)->get();
            }else{
                $zones = DB::table('zones')->select('id','name_ar as name')->where('country_id',$country_id)->get();
            }
            
            return response()->json([
                "result" => $zones,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Sorry we not provide service at this country',
                "status" => 0
            ]);
        }
    }
    
    public function profile_picture_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'profile_picture' => 'required'
            
        ]);

        if ($validator->fails()) {
          return $this->sendError($validator->errors());
        }
        
        if (Driver::where('id',$input['id'])->update($input)) {
            return response()->json([
                "message" => 'Success',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong...',
                "status" => 0
            ]);
        }

    }
        
    public function profile_update(Request $request)
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'driver_id' => 'required'
                
            ]);
            $input['id'] = $input['driver_id'];
            unset($input['driver_id']);
            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            }
            if($request->password){
                $options = [
                    'cost' => 12,
                ];
                $input['password'] = password_hash($input["password"], PASSWORD_DEFAULT, $options);
                $input['status'] = 1;
            }else{
                unset($input['password']);
            }

            if (Driver::where('id',$input['id'])->update($input)) {
                return response()->json([
                    "result" => Driver::select('id', 'first_name', 'last_name', 'phone_with_code','email','profile_picture','password','status')->where('id',$input['id'])->first(),
                    "message" => 'Success',
                    "status" => 1
                ]);
            } else {
                return response()->json([
                    "message" => 'Sorry, something went wrong...',
                    "status" => 0
                ]);
            }

        }
    
     public function driver_earnings(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'date' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $today_earnings = DriverEarning::where('driver_id',$input['id'])->whereDate('created_at','=',$input['date'])->sum("amount");
        $data['today_earnings'] = number_format((float)$today_earnings, 2, '.', '');
        $data['earnings'] = DriverEarning::where('driver_id',$input['id'])->whereDate('created_at','=',$input['date'])->get();
        
        if($data){
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Something went wrong',
                "status" => 0
            ]);
        }
    }
    
    public function get_tutorials(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        if($input['lang'] == 'en'){
            $data = DriverTutorial::select('id','title','description','file','thumbnail_image','status')->orderBy('id', 'DESC')->get();
        }else{
            $data = DriverTutorial::select('id','title_ar as title','description_ar as description','file','thumbnail_image','status')->orderBy('id', 'DESC')->get();
        }
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_vehicles(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = DriverVehicle::where('driver_id',$input['driver_id'])->first();
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_rating(Request $request){
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'ratings' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
            Trip::where('id',$input['trip_id'])->update([ 'customer_rating' => $input['ratings']]);
            $trip = Trip::where('id',$input['trip_id'])->first();
            if(is_object($trip)){
                 $this->customer_rating($trip->customer_id);
                 return response()->json([
                    "result" => $trip,
                    "message" => 'Success',
                    "status" => 1
                    ]);
                 }else{
            return response()->json([
                "message" => 'Something went wrong',
                "status" => 0
            ]);
        }

    }
    
    public function customer_rating($customer_id)
    {
        $ratings_data = Trip::where('customer_id',$customer_id)->where('customer_rating','!=', '0')->get();
        $data_sum = Trip::where('customer_id',$customer_id)->get()->sum("customer_rating");
        $data = $data_sum / count($ratings_data);
        if($data){
            Customer::where('id',$customer_id)->update(['overall_ratings'=>number_format((float)$data, 1, '.', ''), 'no_of_ratings'=> count($ratings_data)]);
        }
    }
    
    public function ride_completeion_mail(Request $request){
        $input = $request->all();
        $ride_id = $id;
        $data = DB::table('trips')
                ->leftJoin('customers','customers.id','trips.customer_id')
                ->leftJoin('drivers','drivers.id','trips.driver_id')
                ->leftJoin('payment_methods','payment_methods.id','trips.payment_method')
                ->leftJoin('driver_vehicles','driver_vehicles.id','trips.vehicle_id')
                ->leftJoin('vehicle_categories','vehicle_categories.id','driver_vehicles.vehicle_type')
                ->leftJoin('booking_statuses','booking_statuses.id','trips.status')
                ->select('trips.*','customers.first_name as customer_name','customers.email as email','drivers.first_name as driver_name','drivers.profile_picture','payment_methods.payment as payment_method','driver_vehicles.brand as vehicle_brand','driver_vehicles.color','driver_vehicles.vehicle_name as vehicle_name','driver_vehicles.vehicle_number as vehicle_number','booking_statuses.status_name','vehicle_categories.vehicle_type')
                ->where('trips.id',$ride_id)
                ->first();
        $mail_header = array("data" => $data);
        $this->ride_completeion($mail_header,'Ride Completed Successfully',$data->email);
    }
    
    public function image_upload(Request $request){

        $input = $request->all();
        $validator = Validator::make($input, [
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/drivers');
            $image->move($destinationPath, $name);
            return response()->json([
                "result" => 'drivers/'.$name,
                "message" => 'Success',
                "status" => 1
            ]);
            
        }
    }
    
    public function get_driver_settings(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = DB::table('drivers')->select('driver_hiring_status','shared_ride_status')->where('id','=',$input['driver_id'])->first();
        return response()->json([
            "data" =>$data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function change_driver_settings(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'shared_ride_status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = DB::table('drivers')->where('id','=',$input['id'])->update($input);
        return response()->json([
            "data" =>$data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    //v4
    public function driver_withdrawal_history(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data['wallet_amount'] = Driver::where('id',$input['id'])->value('wallet');
        if($input['lang'] == 'en'){
            $data['withdraw'] =  DB::table('driver_withdrawals')
                    ->leftjoin('statuses', 'statuses.id', '=', 'driver_withdrawals.status')
                    ->select('driver_withdrawals.*', 'statuses.name')
                    ->where('driver_withdrawals.driver_id',$input['id'])
                    ->get();
        }else{
            $data['withdraw'] =  DB::table('driver_withdrawals')
                    ->leftjoin('statuses', 'statuses.id', '=', 'driver_withdrawals.status')
                    ->select('driver_withdrawals.*', 'statuses.name_ar as name')
                    ->where('driver_withdrawals.driver_id',$input['id'])
                    ->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function driver_withdrawal_request(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input['status'] = 11;
        $vendor = DriverWithdrawal::create($input);
        $old_wallet = Driver::where('id',$input['driver_id'])->value('wallet');
        $new_wallet = $old_wallet - $input['amount'];
        Driver::where('id',$input['driver_id'])->update([ 'wallet' => $new_wallet ]);
        return response()->json([
            "message" => 'success',
            "status" => 1
        ]);
    }
    
    public function payment_methods(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = PaymentMethod::select('id','payment','payment_type','icon','status')->where('status',1)->where('payment_type',2)->get();
        }else{
            $data = PaymentMethod::select('id','payment_ar as payment','payment_type','icon','status')->where('status',1)->where('payment_type',2)->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_wallet(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data['driver_id'] = $input['id'];
        $data['type'] = 1;
        $data['message'] ="Added to wallet";
        $data['message_ar'] = "تمت إضافه إلى المحظة";
        $data['amount'] = $input['amount'];
        DriverWalletHistory::create($data);
        $recharge['driver_id'] = $input['id'];
        $recharge['amount'] = $input['amount'];
        DriverRecharge::create($recharge);
        $old_wallet = Driver::where('id',$input['id'])->value('wallet');
        $new_wallet = $old_wallet + $input['amount'];
        Driver::where('id',$input['id'])->update([ 'wallet' => $new_wallet ]);
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function driver_wallet(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data['wallet'] = Driver::where('id',$input['id'])->value('wallet');
        
        $data['all'] = DriverWalletHistory::select('id','type','message','amount','created_at','updated_at')->where('driver_id',$input['id'])->orderBy('id', 'desc')->get();
        $data['expenses'] = DriverWalletHistory::select('id','type','message','amount','created_at','updated_at')->where('driver_id',$input['id'])->where('type',2)->orderBy('id', 'desc')->get();
        $data['receives'] = DriverWalletHistory::select('id','type','message','amount','created_at','updated_at')->where('driver_id',$input['id'])->where('type',1)->orderBy('id', 'desc')->get();
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function vehicle_type_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = VehicleCategory::select('id','vehicle_type','description','base_fare','price_per_km','active_icon','inactive_icon','status')->get();
        }else{
            $data = VehicleCategory::select('id','vehicle_type_ar as vehicle_type','description_ar as description','base_fare','price_per_km','active_icon','inactive_icon','status')->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function vehicle_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required',
            'vehicle_type' => 'required',
            'brand' => 'required',
            'color' => 'required',
            'vehicle_name' => 'required',
            'vehicle_number' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input['status'] = 1;
        $driver_vehicles = DriverVehicle::create($input);
        $vehicle_mode = VehicleCategory::where('id',$input['vehicle_type'])->value('vehicle_mode');
    
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        if (is_object($driver_vehicles)) {
            
            //Booking Details
            $booking['booking_status'] = 0;
            $booking['pickup_address'] = "";
            $booking['drop_address'] = "";
            $booking['static_map'] = "";
            $booking['total'] = 0;
            $booking['booking_id'] = 0;
            $booking['customer_name'] = "";
            $booking['trip_type'] = "";
            $booking['trip_type_ar'] = "";
            
            //Location Details
            $geo['lat'] = 0;
            $geo['lng'] = 0;
            $geo['bearing'] = 0;
            
            $newPost = $database
            ->getReference('drivers/'.$input['vehicle_type'].'/'.$input['driver_id'])
            ->update([
                'driver_id' => $input['driver_id'],
                'vehicle_type' => $input['vehicle_type'],
                'vehicle_mode' => $vehicle_mode,
                'vehicle_slug' => DB::table('vehicle_slugs')->where('id',VehicleCategory::where('id',$input['vehicle_type'])->value('vehicle_slug'))->value('slug'),
                'driver_name' => Driver::where('id',$input['driver_id'])->value('first_name'),
                'online_status' => 0,
                'geo' => $geo,
                'booking' => $booking
            ]);
            
            $newPost = $database
            ->getReference('shared/'.$input['driver_id'])
            ->update([
                'booking_id' => 0,
                'pickup_address' => '',
                'drop_address' => '',
                'total' => 0,
                'customer_name' => ''
            ]);

            if (is_object($driver_vehicles)) {
                return response()->json([
                    "result" => $driver_vehicles,
                    "message" => 'Registered Successfully',
                    "status" => 1
                ]);
            } else {
                return response()->json([
                    "message" => 'Sorry, something went wrong !',
                    "status" => 0
                ]);
            }

        }
    }
    
    public function vehicle_details(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = DriverVehicle::where('driver_id',$input['driver_id'])->first();
        $driver = Driver::where('id',$input['driver_id'])->value('driver_approved_status');
        if(is_object($data)){
            $data->driver_approved_status = $driver;
        }
        if(is_object($data))
        {
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Vehicle details not updated',
                "status" => 0
            ]);
        }
    }
    
    public function app_settings()
    {
        $data = AppSetting::first();
        $data->stripe_key = env('STRIPE_KEY');
        $data->stripe_secret = env('STRIPE_API_KEY');
        $data->razorpay_key = env('RAZORPAY_KEY');
        $data->paystack_secret_key = env('PAYSTACK_SECRET_KEY');
        $data->paystack_public_key = env('PAYSTACK_PUBLIC_KEY');
        $data->flutterwave_public_key = env('FLUTTERWAVE_PUBLIC_KEY');
        $data->paypal_client_id = env('PAYPAL_SANDBOX_CLIENT_ID');
        $data->android_latest_version = DB::table('app_versions')->where('platform',1)->orderBy('id', 'desc')->first();
        $data->ios_latest_version = DB::table('app_versions')->where('platform',2)->orderBy('id', 'desc')->first();
        $data->mode = env('MODE');
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function check_phone(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'phone_with_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = array();
        $driver = Driver::where('phone_with_code',$input['phone_with_code'])->first();
        if(is_object($driver)){
            $data['is_available'] = 1;
            $data['otp'] = "";
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            $data['is_available'] = 0;
            $data['otp'] = rand(1000,9999);
            
            $message = "Hi ".env('APP_NAME')." , Your OTP code is:".$data['otp'];
            $this->sendSms($input['phone_with_code'],$message);
            
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
        }
    }
    
    public function login(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'phone_with_code' => 'required',
            'password' => 'required',
            'fcm_token' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $credentials = request(['phone_with_code', 'password']);
        $driver = Driver::where('phone_with_code',$credentials['phone_with_code'])->first();
        if (!($driver)) {
            return response()->json([
                "message" => 'Invalid phone number or password',
                "status" => 0
            ]);
        }
        if (Hash::check($credentials['password'], $driver->password)) {
            if($driver->status == 1){
                $vehicle_status = DriverVehicle::where('driver_id',$driver->id)->first();
                $vehicle_document_status = DB::table('drivers')->join('driver_vehicles','driver_vehicles.driver_id','=','drivers.id')->where('drivers.id',$driver->id)->where('driver_vehicles.vehicle_certificate_status',16)->where('driver_vehicles.vehicle_insurance_status',16)->where('driver_vehicles.vehicle_image_status',16)->where('drivers.id_proof_status',16)->count();
                if($driver->id_proof_status && is_object($vehicle_status) && $vehicle_document_status){
                    Driver::where('id', $driver->id)->update([ 'fcm_token' => $input['fcm_token']]);
                    $driver->vehicle_type = $vehicle_status->vehicle_type;
                    $driver->vehicle_id = $vehicle_status->id;
                    return response()->json([
                        "result" => $driver,
                        "message" => 'Success',
                        "status" => 1
                    ]);   
                }else{
                    Driver::where('id', $driver->id)->update([ 'fcm_token' => $input['fcm_token']]);
                    $driver->vehicle_type = 0;
                    $driver->vehicle_id = 0;
                    return response()->json([
                        "result" => $driver,
                        "message" => 'Success',
                        "status" => 1
                    ]); 
                }
            }else{
                return response()->json([
                    "message" => 'Your account has been blocked',
                    "status" => 0
                ]);
            }
        }else{
            return response()->json([
                "message" => 'Invalid phone number or password',
                "status" => 0
            ]);
        }
    }
    
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'country_code' => 'required',
            'phone_with_code' => 'required',
            'phone_number' => 'required|numeric|digits_between:6,20|unique:drivers,phone_number',
            'email' => 'required|email|regex:/^[a-zA-Z]{1}/|unique:drivers,email',
            'password' => 'required',
            'date_of_birth' => 'required',
            'licence_number' => 'required',
            'fcm_token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $options = [
            'cost' => 12,
        ];
        $input['date_of_birth'] = date('Y-m-d', strtotime($input['date_of_birth']));
        $input['password'] = password_hash($input["password"], PASSWORD_DEFAULT, $options);
        $input['status'] = 1;
        if(@$input['referral_code']){
            $refered_by = $input['referral_code'];
            $referrel_id = Driver::where('referral_code',$refered_by)->value('id');
            if($referrel_id){
                $input['refered_by'] = $refered_by;
            }
        }else{
            $refered_by = '';
            $referrel_id = '';
            $input['refered_by'] = '';
        }
        
        
        $driver = Driver::create($input);
        if(is_object($driver)) {
            if($refered_by != '' && $referrel_id){
                $referral_amount = AppSetting::where('id',1)->value('driver_referral_amount');
                $existing_wallet_amount = Driver::where('referral_code',$refered_by)->value('wallet');
                $wallet = $existing_wallet_amount + $referral_amount;
                Driver::where('referral_code',$refered_by)->update(['wallet' => $wallet]);
                Driver::where('id',$driver->id)->update(['refered_by' => $referrel_id]);
                $content = "Referral Bonus";
                DriverWalletHistory::create([ 'driver_id' => $referrel_id, 'type' => 3, 'message' => $content, 'amount' => $referral_amount]);
            }
            $driver->referral_code = 'DVR'.str_pad($driver->id,5,"0",STR_PAD_LEFT);
            Driver::where('id',$driver->id)->update(['referral_code' => $driver->referral_code]);
            return response()->json([
                "result" => $driver,
                "message" => 'Registered Successfully',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong !',
                "status" => 0
            ]);
        }
    }
    
    public function forgot_password(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'phone_with_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $driver = Driver::where('phone_with_code',$input['phone_with_code'])->first();
        $data['id'] = $driver->id;
        if(is_object($driver)){
            $otp = rand(1000,9999);
            $data['otp'] = $otp;
           
            $message = "Hi".env('APP_NAME')." , Your OTP code is:".$otp;
            $this->sendSms($input['phone_with_code'],$message);
            
            
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Please enter valid phone number',
                "status" => 0
            ]);
        }
    }
    
    public function reset_password(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $options = [
            'cost' => 12,
        ];
        $input['password'] = password_hash($input["password"], PASSWORD_DEFAULT, $options);
        if(Driver::where('id',$input['id'])->update($input)){
            return response()->json([
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
    
    public function change_online_status(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'online_status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $driver = Driver::where('id',$input['id'])->first();
        $vehicle_status = DriverVehicle::where('driver_id',$driver->id)->first();
        $vehicle_document_status = DB::table('drivers')->join('driver_vehicles','driver_vehicles.driver_id','=','drivers.id')->where('drivers.id',$driver->id)->where('driver_vehicles.vehicle_certificate_status',16)->where('driver_vehicles.vehicle_insurance_status',16)->where('driver_vehicles.vehicle_image_status',16)->where('drivers.id_proof_status',16)->count();
        if($driver->id_proof_status && is_object($vehicle_status) && $vehicle_document_status){
            Driver::where('id',$input['id'])->update([ 'online_status' => $input['online_status']]);
            $driver = Driver::where('id',$input['id'])->first();
            
            $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
            $database = $factory->createDatabase();
            $newPost = $database
            ->getReference('/drivers/'.$vehicle_status->vehicle_type.'/'.$driver->id)
            ->update([
                'online_status' => (int) $input['online_status']
            ]);
            
            return response()->json([
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            Driver::where('id',$input['id'])->update([ 'online_status' => $input['online_status']]);
            if(!is_object($vehicle_status)){
                return response()->json([
                    "result" => $driver,
                    "message" => 'Vehicle details need to update',
                    "status" => 2
                ]);
            }else{
                return response()->json([
                    "result" => $driver,
                    "message" => 'Vehicle documents need to update',
                    "status" => 3
                ]);
            }
        }
    }
    
    public function driver_dashboard(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $today_earnings = DriverEarning::where('driver_id',$input['id'])->whereDay('created_at', now()->day)->sum("amount");
        $result['today_earnings'] = number_format((float)$today_earnings, 2, '.', '');
        $result['today_bookings'] = Trip::where('driver_id',$input['id'])->where('status','!=', '6')->where('status','!=', '7')->whereDay('created_at', now()->day)->count();
        $result['today_completed_bookings'] = Trip::where('driver_id',$input['id'])->where('status',5)->whereDay('updated_at', now()->day)->count();
        $result['online_status'] = Driver::where('id',$input['id'])->value('online_status');
        $result['pending_hire_bookings'] = DB::table('driver_hiring_requests')->where('driver_id',$input['id'])->where('status',1)->count();
        $result['wallet'] = Driver::where('id',$input['id'])->value('wallet');
        $result['booking_id'] = Trip::where('driver_id',$input['id'])->whereIn('status',[1,2,3,4])->value('id');
        if(!$result['booking_id']){
            $result['booking_id'] = 0;
            $result['trip_type'] = 0;
        }else{
            $result['trip_type'] = Trip::where('id',$result['booking_id'])->value('trip_type');
        }
        $vehicle_details = DriverVehicle::where('driver_id',$input['id'])->first();
        $driver = Driver::where('id',$input['id'])->first();
        $vehicle_document_status = DB::table('drivers')->join('driver_vehicles','driver_vehicles.driver_id','=','drivers.id')->where('drivers.id',$driver->id)->where('driver_vehicles.vehicle_certificate_status',16)->where('driver_vehicles.vehicle_insurance_status',16)->where('driver_vehicles.vehicle_image_status',16)->where('drivers.id_proof_status',16)->count();
        if($driver->id_proof_status && is_object($vehicle_details) && $vehicle_document_status){
            $result['sync_status'] = 1;
            $result['vehicle_type'] = $vehicle_details->vehicle_type;
            $result['vehicle_id'] = $vehicle_details->id;
        }else{
            if(!is_object($vehicle_details)){
                $result['sync_status'] = 2;
                $result['vehicle_type'] = 0;
                $result['vehicle_id'] = 0;
            }else{
                $result['sync_status'] = 3;
                $result['vehicle_type'] = $vehicle_details->vehicle_type;
                $result['vehicle_id'] = $vehicle_details->id;
            }
        }
        if($result){
            return response()->json([
                "result" => $result,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Something went wrong',
                "status" => 0
            ]);
        }
    }
    
    public function get_documents(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = [];
        $driver_details = Driver::where('id',$input['driver_id'])->first();
        $vehicle_details = DriverVehicle::where('driver_id',$input['driver_id'])->first();
        $data[0]['path'] = $driver_details->id_proof;
        $data[0]['document_name'] = 'id_proof';
        $data[0]['status'] = $driver_details->id_proof_status;
        $data[1]['path'] = $vehicle_details->vehicle_image;
        $data[1]['document_name'] = 'vehicle_image';
        $data[1]['status'] = $vehicle_details->vehicle_image_status;
        $data[2]['path'] = $vehicle_details->vehicle_certificate;
        $data[2]['document_name'] = 'vehicle_certificate';
        $data[2]['status'] = $vehicle_details->vehicle_certificate_status;
        $data[3]['path'] = $vehicle_details->vehicle_insurance;
        $data[3]['document_name'] = 'vehicle_insurance';
        $data[3]['status'] = $vehicle_details->vehicle_insurance_status;
        if($input['lang'] == 'en'){
            $data[0]['status_name'] = Status::where('id',$driver_details->id_proof_status)->value('name');
            $data[1]['status_name'] = Status::where('id',$vehicle_details->vehicle_image_status)->value('name');
            $data[2]['status_name'] = Status::where('id',$vehicle_details->vehicle_certificate_status)->value('name');
            $data[3]['status_name'] = Status::where('id',$vehicle_details->vehicle_insurance_status)->value('name');
        }else{
            $data[0]['status_name'] = Status::where('id',$driver_details->id_proof_status)->value('name_ar');
            $data[1]['status_name'] = Status::where('id',$vehicle_details->vehicle_image_status)->value('name_ar');
            $data[2]['status_name'] = Status::where('id',$vehicle_details->vehicle_certificate_status)->value('name_ar');
            $data[3]['status_name'] = Status::where('id',$vehicle_details->vehicle_insurance_status)->value('name_ar');
        }
        $result['documents'] = $data;
        $result['details']['vehicle_id'] = $vehicle_details->id;
        return response()->json([
            "result" => $result,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function update_document(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'table' => 'required',
            'update_field' => 'required',
            'update_value' => 'required',
            'find_field' => 'required',
            'find_value' => 'required',
            'status_field' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $update_status = 15;
        
        
        
        DB::table($input['table'])->where($input['find_field'],'=',$input['find_value'])->update([
            $input['update_field'] => $input['update_value'], $input['status_field'] => $update_status
        ]);
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_about(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $about_us = AppSetting::first();
        $contact_details = ContactSetting::first();
        $data['phone_number'] = $contact_details['phone_number'];
        $data['email'] = $contact_details['email'];
        $data['address'] = $contact_details['address'];
        $data['app_version'] = $about_us['driver_app_version'];
        if($input['lang'] == 'en'){
            $data['about_us'] = $about_us['about_us'];
        }else{
            $data['about_us'] = $about_us['about_us_ar'];
        }
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_profile(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $result = Driver::where('id',$input['driver_id'])->first();
        if (is_object($result)) {
            return response()->json([
                "result" => $result,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Sorry, something went wrong...',
                "status" => 0
            ]);
        }
    }
    
    public function faq(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = Faq::select('id','question','answer','status')->where('status',1)->where('user_type_id',2)->get();
        }else{
            $data = Faq::select('id','question_ar as question','answer_ar as answer','status')->where('status',2)->where('user_type_id',1)->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_customer_notification_messages(Request $request)
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
            $data = NotificationMessage::select('id','title','message','status','created_at')->where('status',1)->where('type',2)->orderBy('id', 'DESC')->get();
        }else{
            $data = NotificationMessage::select('id','title_ar as title','message_ar as message','status','created_at')->where('status',1)->where('type',2)->orderBy('id', 'DESC')->get();
        }
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_bank_kyc_details(Request $request){
        $input = $request->all();
        $kyc_details = DriverBankKycDetail::where('driver_id', $input['driver_id'])->first();
        if(is_object($kyc_details)){
            return response()->json([
                "result" => $kyc_details,
                "message" => 'Success',
                "status" => 1
            ]);
        }else{
            return response()->json([
                "message" => 'Still not updated',
                "status" => 0
            ]);
        }
        
    }
    
    public function bank_kyc_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'driver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $is_details_exist = DriverBankKycDetail::where('driver_id', $input['driver_id'])->first();
        if(is_object($is_details_exist)){
            $update = DriverBankKycDetail::where('driver_id', $input['driver_id'])->update($input);  
        }else{
             $update =  DriverBankKycDetail::create($input);   
        }
        if ($update) {
            return response()->json([
                "result" => DriverBankKycDetail::select('id','driver_id', 'bank_name', 'bank_account_number','ifsc_code','aadhar_number','pan_number')->where('driver_id', $input['driver_id'])->first(),
                "message" => 'Success',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong...',
                "status" => 0
            ]);
        }
    }
    
    public function profile_image_upload(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/drivers');
            $image->move($destinationPath, $name);
            return response()->json([
                "result" => 'drivers/'.$name,
                "message" => 'Success',
                "status" => 1
            ]);
            
        }
    }
    
    public function sendError($message) {
        $message = $message->all();
        $response['error'] = "validation_error";
        $response['message'] = implode('',$message);
        $response['status'] = "0";
        return response()->json($response, 200);
    } 

}
