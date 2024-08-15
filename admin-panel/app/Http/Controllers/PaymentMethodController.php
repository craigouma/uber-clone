<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentMethod;
use App\PaymentResponse;
use App\AppSetting;
use Cartalyst\Stripe\Stripe;

class PaymentMethodController extends Controller
{
    public function payment(Request $request)
    {   
        $input = $request->all();
        
            $data = PaymentMethod::where('status',1)->select('id','payment_mode','icon','status')->get();
        
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
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
                'description' => 'For laundry service booking'
            ]);
            
            $data['order_id'] = 0;
            $data['customer_id'] = $input['customer_id'];
            $data['payment_mode'] = 2;
            $data['payment_response'] = $charge['id'];
            
            if(PaymentResponse::create($data)){
                return response()->json([
                    "result" => $charge['id'],
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
        catch (customException $e) {
            return response()->json([
                "message" => 'Sorry something went wrong',
                "status" => 0
            ]);
        }
    }
    
    public function create_stripe($email,$name){
        
    }
}
