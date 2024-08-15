<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Customer;
use App\Models\TripType;
use App\Models\TripSubType;
use App\Faq;
use App\ReferralSetting;
use App\PrivacyPolicy;
use App\CancellationReason;
use App\DriverVehicle;
use App\NotificationMessage;
use App\CustomerSosContact;
use App\ContactSetting;
use App\Currency;
use App\Country;
use App\Driver;
use App\ComplaintCategory;
use App\ComplaintSubCategory;
use App\Complaint;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\CustomerSubscriptionHistory;
use App\PaymentMethod;
use App\VehicleCategory;
use App\Models\CustomerOffer;
use App\CustomerWalletHistory;
use App\InstantOffer;
use App\PromoCode;
use App\CustomerFavourite;
use App\AppSetting;
use App\Trip;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Mail;
use DateTime;
class CustomerController extends Controller
{
    public function update_phone_number(Request $request){
        
        $input = $request->all();
        $validator = Validator::make($input, [
        	'phone_number' => 'required',
        	'id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $exist = Customer::where('email',$input['email'])->where('id','!=',$input['id'])->value('id');
        if(!$exist){
            $token = md5($input['email'].time());
            $check_verified = Customer::where('email',$input['email'])->where('id',$input['id'])->where('email_verification_status',1)->value('id');
            if(!$check_verified){
               $mail_header = array("token" => $token);
                $this->send_verification_email($mail_header,'Verify your email',$input['email']);
                Customer::where('id',$input['id'])->update(['email_verification_status' => 0, 'email_verification_code' => $token, 'email' => $input['email']]);
                return response()->json([
                    "message" => 'Success',
                    "status" => 1
                ]); 
            }else{
                return response()->json([
                    "message" => 'Success',
                    "status" => 1
                ]);
            }
        }else{
            return response()->json([
                "message" => 'Sorry already exist',
                "status" => 0
            ]);
        }
    }
    
    public function verify_email($token){
        $customer = Customer::where('email_verification_code',$token)->first();   
        if(is_object($customer)){
            if($customer->email_verification_status == 0){
                Customer::where('id',$customer->id)->update([ 'email_verification_status' => 1 ]);
                echo "Successfully verified";
            }else{
                echo "This email already verified";
            }    
        }else{
            echo "Sorry invalid link";
        }
        
    }
    
    
    
    
    
    public function wallet_payment_methods(Request $request)
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
            $data = PaymentMethod::select('id','payment','payment_type','icon','status')->where('status',1)->whereNotIn('payment_type', [1,2,3,4])->where('country_id',$input['country_id'])->get();
        }else{
            $data = PaymentMethod::select('id','payment_ar as payment','payment_type','icon','status')->where('status',1)->whereNotIn('payment_type', [1,2,3,4])->where('country_id',$input['country_id'])->get();
        }
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function customer_offers(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = CustomerOffer::select('id','title','description','image','view_status','type','ref_id','status')->where('customer_id',$input['customer_id'])->where('status',1)->orderBy('view_status', 'DESC')->get();
        }else{
            $data = CustomerOffer::select('id','title_ar as title','description_ar as description','image','view_status','type','ref_id','status')->where('customer_id',$input['customer_id'])->where('status',1)->orderBy('view_status', 'DESC')->get();
        }
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function update_view_status(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'offer_id' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        CustomerOffer::where('customer_id',$input['customer_id'])->where('id',$input['offer_id'])->update([ 'view_status' => $input['status']]);
        $offer = CustomerOffer::where('id',$input['offer_id'])->first();
        if(is_object($offer) && $offer->type == 1){
            $instant = InstantOffer::where('id',$offer->ref_id)->first();
            $data['country_id'] = Customer::where('id',$input['customer_id'])->value('country_id');
            $data['customer_id'] = $input['customer_id'];
            $data['promo_name'] = $instant->offer_name;
            $data['promo_code'] = $this->getToken(8);
            $data['description'] = $instant->offer_description;
            $data['promo_type'] = $instant->discount_type;
            $data['discount'] = $instant->discount;
            $data['redemptions'] = 1;
            $data['status'] = 1;
            PromoCode::create($data);
        }
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited
    
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }
    
        return $token;
    }

     public function stripe_payment(Request $request){
        $input = $request->all();
        $stripe = new Stripe();
        $currency_code = AppSetting::value('currency_short_code');
        
        try {
            $charge = $stripe->charges()->create([
                'source' => $input['token'],
                'currency' => $currency_code,
                'amount'   => $input['amount'],
                'description' => 'For booking'
            ]);
            
            $data['order_id'] = 0;
            $data['customer_id'] = $input['customer_id'];
            $data['payment_mode'] = 2;
            $data['payment_response'] = $charge['id'];
            
                return response()->json([
                    "result" => $charge['id'],
                    "message" => 'Success',
                    "status" => 1
                ]);
            
        }
        catch (customException $e) {
            return response()->json([
                "message" => 'Sorry something went wrong',
                "status" => 0
            ]);
        }
    }
    
    
    public function get_gender(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $gender = Customer::where('id',$input['customer_id'])->value('gender');
        
        return response()->json([
            "result" => $gender,
            "message" => 'Success',
            "status" => 1
        ]);
        
    }
    
    public function get_subscription_list(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
        ]); 
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $data = Subscription::get();
        $sub_id = Customer::where('id',$input['customer_id'])->value('current_sub_id');
        $subscription = "";
        if($sub_id){
            $subscription = Subscription::where('id',$sub_id)->first();
        }
        return response()->json([
            "result" => $data,
            "current_subscription" => $subscription,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_subscription(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
            'sub_id' =>'required',
        ]); 
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        
        $data['customer_id'] = $input['customer_id'];
        $data['sub_id'] = $input['sub_id'];
        $sub = DB::table('subscriptions')->where('id',$input['sub_id'])->first();
        $data['purchased_at'] = date("Y/m/d");
        $data['expiry_at'] = date('Y-m-d', strtotime($data['purchased_at']. ' + '.$sub->validity.' days'));
        CustomerSubscriptionHistory::create($data);
        
        Customer::where('id',$input['customer_id'])->update([ 'current_sub_id' => $input['sub_id'], 'subscription_trips' => $sub->free_bookings, 'sub_purchased_at' => date("Y-m-d"), 'sub_expired_at' => date('Y/m/d', strtotime("+".$sub->validity." days"))]);
        
        return response()->json([
            "result" => Customer::where('id',$input['customer_id'])->select('current_sub_id','subscription_trips','sub_purchased_at','sub_expired_at')->first(),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_subscription_details(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
        ]); 
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $customer = Customer::where('id',$input['customer_id'])->select('current_sub_id','subscription_trips','sub_purchased_at','sub_expired_at')->first();

        if($customer->current_sub_id != 0){
            //Expiry check
            $today = (new DateTime())->format('Y-m-d'); 
            $expiry = (new DateTime($customer->sub_expired_at))->format('Y-m-d');
            if(strtotime($today) > strtotime($expiry)){
                $customer = Customer::where('id',$input['customer_id'])->update([ 'current_sub_id' => 0, 'subscription_trips' => 0, 'sub_purchased_at' => '', 'sub_expired_at' => ''])->first();
            }else if($customer->subscription_trips == 0){
                $customer = Customer::where('id',$input['customer_id'])->update([ 'current_sub_id' => 0, 'subscription_trips' => 0, 'sub_purchased_at' => '', 'sub_expired_at' => ''])->first();
            }
        }
        
        return response()->json([
            "result" => $customer,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_package(Request $request)
    {
        $input = $request->all();
        if($input['lang'] == 'en'){
            $data = Package::select('id','package_name','hours','kilometers')->get();
        }else{
            $data = Package::select('id','package_name_ar as package_name','hours','kilometers')->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function alert_message(Request $request){

        $input = $request->all();
        $validator = Validator::make($input, [
        	'trip_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $customer_id = Trip::where('id',$input['trip_id'])->value('customer_id');
        $customer = Customer::where('id',$customer_id)->value('phone_with_code');
        //print_r($customer);exit;

        if($customer){
            $message = "Hi, from".env('APP_NAME'). "  , Your driver will reach within 2mins, Hurry up!";
            $this->sendSms($customer,$message);
            return response()->json([
                "message" => 'Success',
                "status" => 1
            ]);
        }

    }
    
    public function customer_chat($id)
    {
       return view('customer_chat');
    }
    
    //v4
    public function get_home(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' =>'required',
            'customer_id' => 'required'
        ]); 
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        //Get trip types
        if($input['lang'] == 'en'){
            $trip_types = TripType::select('id','name','active_icon','inactive_icon','vehicle_mode','status')->where('status',1)->orderBy('sort','asc')->get();
        }else{
           $trip_types = TripType::select('id','name_ar as name','active_icon','inactive_icon','vehicle_mode','status')->where('status',1)->orderBy('sort','asc')->get();
        }
       
        foreach($trip_types as $key => $value){
            if($input['lang'] == 'en'){
                $sub_type = TripSubType::select('id','trip_sub_type')->where('trip_type',$value->id)->get();
                $sub_type_labels = TripSubType::where('trip_type',$value->id)->pluck('trip_sub_type');
            }else{
                $sub_type = TripSubType::select('id','trip_sub_type_ar as trip_sub_type')->where('trip_type',$value->id)->get();
                $sub_type_labels = TripSubType::where('trip_type',$value->id)->pluck('trip_sub_type_ar as trip_sub_type');
            }
            $trip_types[$key]['trip_sub_type'] = $sub_type;
            $trip_types[$key]['trip_sub_type_labels'] = $sub_type_labels;
        }
        
        $data['trip_types'] = $trip_types;
        $data['customer_favourites'] = CustomerFavourite::where('customer_id',$input['customer_id'])->get();
        if($input['lang'] == 'en'){
            $data['packages'] = Package::select('id','package_name','hours','kilometers')->get();
        }else{
            $data['packages'] = Package::select('id','package_name_ar as package_name','hours','kilometers')->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_favourite(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]); 
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $check_exisitng = CustomerFavourite::where('lat',$input['lat'])->where('lng',$input['lng'])->first();
        if(!is_object($check_exisitng)){
            $data = CustomerFavourite::create($input);
        }
        return response()->json([
            "result" => CustomerFavourite::where('customer_id',$input['customer_id'])->get(),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_wallet(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data['wallet'] = Customer::where('id',$input['id'])->value('wallet');
        
            $data['all'] = CustomerWalletHistory::select('id','type','message','amount','created_at','updated_at')->where('customer_id',$input['id'])->orderBy('id', 'desc')->get();
            $data['expenses'] = CustomerWalletHistory::select('id','type','message','amount','created_at','updated_at')->where('customer_id',$input['id'])->where('type',2)->orderBy('id', 'desc')->get();
            $data['receives'] = CustomerWalletHistory::select('id','type','message','amount','created_at','updated_at')->where('customer_id',$input['id'])->where('type',1)->orderBy('id', 'desc')->get();
        
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
    
    public function add_wallet(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data['customer_id'] = $input['id'];
        $data['type'] = 1;
        $data['message'] ="Added to wallet";
        $data['message_ar'] ="يا إى المحفظة";
        $data['amount'] = $input['amount'];
        $data['transaction_type'] = 1;
        CustomerWalletHistory::create($data);
        
        $old_wallet = Customer::where('id',$input['id'])->value('wallet');
        $new_wallet = $old_wallet + $input['amount'];
        Customer::where('id',$input['id'])->update([ 'wallet' => $new_wallet ]);
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
        $data['app_version'] = $about_us['app_version'];
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
    
    public function add_sos_contact(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
            'name' => 'required',
            'phone_number' => 'required|numeric|digits_between:6,20'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input['status'] = 1;
        $contact = CustomerSosContact::create($input);
        
        if($contact){
            return response()->json([
                "result" => $contact,
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
    
    public function delete_sos_contact(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
            'contact_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        CustomerSosContact::where('id',$input['contact_id'])->delete();
        return response()->json([
            "message" => 'Deleted Successfully',
            "status" => 1
        ]);

    }
    
    public function sos_contact_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $contact = CustomerSosContact::where('customer_id',$input['customer_id'])->get();
        if($contact){
            return response()->json([
                "result" => $contact,
                "message" => 'success',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong !',
                "status" => 0
            ]);
        }
    }
    
    public function sos_sms(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
            'booking_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        
        $customer = Customer::where('id',$input['customer_id'])->first();
        $contacts = CustomerSosContact::where('customer_id',$input['customer_id'])->get();
        $trip = Trip::where('id',$input['booking_id'])->first();
        if($input['lang'] == 'en'){
            $location = "https://maps.google.com/?ll=".$input['latitude'].",".$input['longitude'];
            $message = "Hi, this is  ".$customer->first_name."  i believe i am in danger near ".$location." . Please help me by contacting the authorities.";
            $country_code = $customer->country_code;
            if(count($contacts) > 0){
                foreach($contacts as $key => $value){
                    $this->sendSms($country_code.$value->phone_number,$message);
                }
                return response()->json([
                    "message" => 'SOS activated',
                    "status" => 1
                ]);
            }else{
                return response()->json([
                    "message" => 'Please add emergency numbers in sos settings page',
                    "status" => 0
                ]);
            }
        }else{
            $location = "https://maps.google.com/?ll=".$input['latitude'].",".$input['longitude'];
            $message = "محبا .. ه ".$customer->first_name."  عد أنني في طر قر ".$location." . لرجاء معدت ن طري الاتصال باسلطا.";
            $country_code = $customer->country_code;
            if(count($contacts) > 0){
                foreach($contacts as $key => $value){
                    $this->sendSms($country_code.$value->phone_number,$message);
                }
                return response()->json([
                    "message" => 'ت تعيل SOS',
                    "status" => 1
                ]);
            }else{
                return response()->json([
                    "message" => 'الرا إضاة أقام الطورئ في صفح إعدادات sos',
                    "status" => 0
                ]);
            }
        }
    }
    
    public function get_complaint_categories(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = ComplaintCategory::select('id','complaint_category_name','status')->where('status',1)->get();
        }else{
            $data = ComplaintCategory::select('id','complaint_category_name_ar as complaint_category_name','status')->where('status',1)->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_complaint_sub_categories(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'complaint_category_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = ComplaintSubCategory::select('id','complaint_sub_category_name','short_description','status')->where('complaint_category',$input['complaint_category_id'])->where('status',1)->get();
        }else{
            $data = ComplaintSubCategory::select('id','complaint_sub_category_name_ar as complaint_sub_category_name','short_description_ar as short_description','status')->where('complaint_category',$input['complaint_category_id'])->where('status',1)->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function add_complaint(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'complaint_category' => 'required',
            'complaint_sub_category' => 'required',
            'subject' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input['status'] = DB::table('statuses')->where('type','customer_complaints')->value('id');
        $trip = Trip::where('id',$input['trip_id'])->first();
        $input['customer_id'] = $trip->customer_id;
        $input['driver_id'] = $trip->driver_id;
        $data = Complaint::create($input);
        return response()->json([
            "message" => 'Success',
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
    
    public function check_email(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'email' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if(@$input['id']){
            $customer = Customer::where('id',$input['id'])->first();
            if($customer->email_verification_status == 0){
                $email = $customer->email;
                $token = md5($email.time());
                $mail_header = array("token" => $token);
                $this->send_verification_email($mail_header,'Verify your email',$email);
                Customer::where('id',$input['id'])->update([ 'email_verification_code' => $token] );
                return response()->json([
                    "message" => 'Success',
                    "status" => 1
                ]); 
            }else{
                return response()->json([
                    "message" => 'Your email account already verified',
                    "status" => 1
                ]);
            }
        }else{
            //Check mail
            $exist = Customer::where('email',$input['email'])->value('id');
            if(!$exist){
                return response()->json([
                    "message" => 'Success',
                    "status" => 1
                ]);
            }else{
                return response()->json([
                    "message" => 'Sorry already exist',
                    "status" => 0
                ]);
            }   
        }
    }
    
    public function register(Request $request)
    {
        $input = $request->all();
        $refered_by = $input['referral_code'];
        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'country_code' => 'required',
            'phone_with_code' => 'required',
            'phone_number' => 'required|numeric|digits_between:6,20|unique:customers,phone_number',
            'email' => 'required|email|regex:/^[a-zA-Z]{1}/|unique:customers,email',
            'password' => 'required',
            'fcm_token' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $options = [
            'cost' => 12,
        ];
        $input['password'] = password_hash($input["password"], PASSWORD_DEFAULT, $options);
        $input['status'] = 1;
        $input['referral_code'] = '';
        $referrel_id = Customer::where('referral_code',$refered_by)->value('id');
        if($referrel_id){
            $input['refered_by'] = $refered_by;
        }else{
            $input['refered_by'] = '';
        }
        $input['profile_picture'] = "customers/avatar.jpg";
        $customer = Customer::create($input);
        
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        if (is_object($customer)) {
            if($refered_by != '' && $referrel_id){
                $referral_amount = AppSetting::where('id',1)->value('referral_amount');
                $existing_wallet_amount = Customer::where('referral_code',$refered_by)->value('wallet');
                $wallet = $existing_wallet_amount + $referral_amount;
                Customer::where('referral_code',$refered_by)->update(['wallet' => $wallet]);
                Customer::where('id',$customer->id)->update(['refered_by' => $referrel_id]);
                $content = "Referral Bonus";
                CustomerWalletHistory::create(['customer_id' => $referrel_id, 'type' => 3, 'message' => $content, 'amount' => $referral_amount, 'transaction_type' => 1  ]);
            }
            $customer->referral_code = 'CUS'.str_pad($customer->id,5,"0",STR_PAD_LEFT);
            Customer::where('id',$customer->id)->update(['referral_code' => $customer->referral_code]);
            $token = md5($input['email'].time());
            $mail_header = array("token" => $token);
            $this->send_verification_email($mail_header,'Verify your email',$input['email']);
            $newPost = $database
            ->getReference('customers/'.$customer->id)
            ->update([
                'booking_id' => 0,
                'booking_status' => 0,
                'is_searching' => 0,
                'customer_name' => $customer->first_name
            ]);
            
            return response()->json([
                "result" => $customer,
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
    
    public function test_mail(){
        $data['first_name'] = "Sarath";
        $data['last_name'] = "Sarath";
        $data['email'] = "Sarath";
        $data['country_code'] = "Sarath";
        $data['phone_number'] = "Sarath";
        $data['phone_with_code'] = "Sarath";
        $mail_header = array("data" => $data);
        $this->send_query_mail($mail_header,'CAB2U - Query Received','menpanitaxi2022@gmail.com');
    }
    public function get_vehicle_categories(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required',
            'vehicle_mode' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = VehicleCategory::select('id','vehicle_type','base_fare','price_per_km','active_icon','inactive_icon','description','status')->where('vehicle_mode',$input['vehicle_mode'])->get();
        }else{
            $data = VehicleCategory::select('id','vehicle_type_ar as vehicle_type','base_fare','price_per_km','active_icon','inactive_icon','description_ar as description','status')->where('vehicle_mode',$input['vehicle_mode'])->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
        
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
    
    public function delete_favourite(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required'
        ]); 
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        CustomerFavourite::where('id',$input['id'])->delete();
        return response()->json([
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function update_email(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'email' => 'required|email|regex:/^[a-zA-Z]{1}/',
        	'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $exist = Customer::where('email',$input['email'])->where('id','!=',$input['id'])->value('id');
        if(!$exist){
            $token = md5($input['email'].time());
            $check_verified = Customer::where('email',$input['email'])->where('id',$input['id'])->where('email_verification_status',1)->value('id');
            if(!$check_verified){
               $mail_header = array("token" => $token);
                $this->send_verification_email($mail_header,'Verify your email',$input['email']);
                Customer::where('id',$input['id'])->update(['email_verification_status' => 0, 'email_verification_code' => $token, 'email' => $input['email']]);
                return response()->json([
                    "message" => 'Success',
                    "status" => 1
                ]); 
            }else{
                return response()->json([
                    "message" => 'Success',
                    "status" => 1
                ]);
            }
        }else{
            return response()->json([
                "message" => 'Sorry already exist',
                "status" => 0
            ]);
        }
    }
    
    public function profile_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required'
        ]);
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
        if (Customer::where('id',$input['id'])->update($input)) {
            return response()->json([
                "result" => Customer::where('id',$input['id'])->first(),
                "message" => 'Success',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong',
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
            $data = Faq::select('id','question','answer','status')->where('status',1)->where('user_type_id',1)->get();
        }else{
            $data = Faq::select('id','question_ar as question','answer_ar as answer','status')->where('status',1)->where('user_type_id',1)->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_favourites(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' =>'required',
        ]); 
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = CustomerFavourite::where('customer_id',$input['customer_id'])->get();
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
        $customer = Customer::where('phone_with_code',$input['phone_with_code'])->first();
        if(is_object($customer)){
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
            
            $message = $message = "Hi ".env('APP_NAME'). "  , Your OTP code is:".$data['otp'];
            $this->sendSms($input['phone_with_code'],$message);
            
            return response()->json([
                "result" => $data,
                "message" => 'Success',
                "status" => 1
            ]);
        }
    }
    
    public function get_customer_notification_messages(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = NotificationMessage::select('id','title','message','status','created_at')->where('status',1)->where('type',1)->orderBy('id', 'DESC')->get();
        }else{
            $data = NotificationMessage::select('id','title_ar as title','message_ar as message','status','created_at')->where('status',1)->where('type',1)->orderBy('id', 'DESC')->get();
        }
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function forgot_password(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
        	'phone_with_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $customer = Customer::where('phone_with_code',$input['phone_with_code'])->first();
        if(is_object($customer)){
            $data['id'] = $customer->id;
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
        $customer = Customer::where('phone_with_code',$credentials['phone_with_code'])->first();
        if(!($customer)){
            return response()->json([
                "message" => 'Invalid phone number or password',
                "status" => 0
            ]);
        }
        if(Hash::check($credentials['password'], $customer->password)) {
            if($customer->status == 1){
                Customer::where('id',$customer->id)->update([ 'fcm_token' => $input['fcm_token']]);
                return response()->json([
                    "result" => $customer,
                    "message" => 'Success',
                    "status" => 1
                ]);   
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
    
    public function privacy_policies(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = PrivacyPolicy::select('id','title','description','status')->where('status',1)->where('user_type_id',1)->get();
        }else{
            $data = PrivacyPolicy::select('id','title_ar as title','description_ar as description','status')->where('status',1)->where('user_type_id',1)->get();
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
            'customer_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $result = Customer::where('id',$input['customer_id'])->first();
        if($result->current_sub_id){
            $result->current_sub = Subscription::where('id',$result->current_sub_id)->value('sub_name');
        }else{
            $result->current_sub = "";
        }
        if (is_object($result)) {
            if($input['lang'] == 'ar'){
                if($result->gender == 0){
                    $result->gender_name = "قم بتحدي جنس";
                }else if($result->gender == 1){
                    $result->gender_name = "ذر";
                }else if($result->gender == 2){
                    $result->gender_name = "أنثى";
                }
            }else{
                if($result->gender == 0){
                    $result->gender_name = "Update your gender";
                }else if($result->gender == 1){
                    $result->gender_name = "Male";
                }else if($result->gender == 2){
                    $result->gender_name = "Female";
                }
            }
            return response()->json([
                "result" => $result,
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
        if(Customer::where('id',$input['id'])->update($input)){
            return response()->json([
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
    
    public function profile_picture_upload(Request $request){
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
            $destinationPath = public_path('/uploads/customers');
            $image->move($destinationPath, $name);
            return response()->json([
                "result" => 'customers/'.$name,
                "message" => 'Success',
                "status" => 1
            ]);
        }
    }
    
    public function get_promo_codes(Request $request){
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
            $codes = PromoCode::select('id','promo_name','promo_code','description','promo_type','discount','redemptions','min_fare','max_discount_value','status')->where('customer_id',$input['customer_id'])->orwhere('customer_id',0)->get();
            
            foreach($codes as $key => $value){
                $using_count = CustomerPromoHistory::where('customer_id',$input['customer_id'])->where('promo_id',$value->id)->where('status',1)->count();
                if($value->redemptions > $using_count){
                    array_push($data,$value);
                }
            }
        }else{
            $data = [];
            $codes = PromoCode::select('id','promo_name_ar as promo_name','promo_code_ar as promo_code','description_ar as description','promo_type','discount','redemptions','min_fare','max_discount_value','status')->where('customer_id',$input['customer_id'])->orwhere('customer_id',0)->get();
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
    
    public function add_rating(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'trip_id' => 'required',
            'ratings' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        Trip::where('id',$input['trip_id'])->update([ 'ratings' => $input['ratings']]);
        $trip = Trip::where('id',$input['trip_id'])->first();
        if(is_object($trip)){
            $this->calculate_rating($trip->driver_id);
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
    
    public function calculate_rating($driver_id)
    {
        $ratings_data = Trip::where('driver_id',$driver_id)->where('ratings','!=', '0')->get();
        $data_sum = Trip::where('driver_id',$driver_id)->get()->sum("ratings");
        $data = $data_sum / count($ratings_data);
        if($data){
            Driver::where('id',$driver_id)->update(['overall_ratings'=>number_format((float)$data, 1, '.', ''), 'no_of_ratings'=> count($ratings_data)]);
        }
    }
    
    public function get_referral_message(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data['details'] = ReferralSetting::select('id','referral_message')->first();
        }else{
            $data['details'] = ReferralSetting::select('id','referral_message_ar as referral_message')->first();
        }
        $data['code'] = Customer::where('id',$input['customer_id'])->value('referral_code');
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
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
        if(Customer::where('id',$input['id'])->update($input)){
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
    
    public function ride_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $dat = Trip::where('customer-id',$input['customer_id'])->get();
        $j=0;
        if(sizeof($dat) > 0){
            foreach($dat as $data){
                $dar = Trip::where('id',$data->id)->first();
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
                "status" => 1
            ]);
        }else {
            return response()->json([
                "message" => 'No trips found',
                "status" => 0
            ]);
        }
    }
    
    public function get_cancellation_policy(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'lang' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        if($input['lang'] == 'en'){
            $data = PrivacyPolicy::select('id','title','description','status')->where('slug','cancellation_policy')->where('user_type_id',1)->first();
        }else{
            $data = PrivacyPolicy::select('id','title_ar as title','description_ar as description','status')->where('slug','cancellation_policy')->where('user_type_id',1)->first();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }
    
    public function get_cancellation_reasons(Request $request){
        $input = $request->all();
        if($input['lang'] == 'en'){
            $data = CancellationReason::select('id','reason','type')->where('type',$input['type'])->get();
        }else{
            $data = CancellationReason::select('id','reason_ar as reason','type')->where('type',$input['type'])->get();
        }
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
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
    
    public function sendError($message) {
        $message = $message->all();
        $response['error'] = "validation_error";
        $response['message'] = implode('',$message);
        $response['status'] = "0";
        return response()->json($response, 200);
    } 

}
