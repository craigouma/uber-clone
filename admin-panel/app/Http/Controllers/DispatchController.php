<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PromoCode;
use App\Trip;
use Illuminate\Support\Facades\DB;
class DispatchController extends Controller
{
    public function find_trip(Request $request)
    {
        $input = $request->all();
        $trip = Trip::where('trip_id',$input['trip_id'])->first();
        $customer_name = DB::table('customers')->where('id',$trip->customer_id)->value('first_name');
        $driver_name = DB::table('drivers')->where('id',$trip->driver_id)->value('first_name');
        $status = DB::table('booking_statuses')->where('id',$trip->status)->value('status_name');
        $trip_type = DB::table('trip_types')->where('id',$trip->trip_type)->value('name');
        $vehicle_type = DB::table('vehicle_categories')->where('id',$trip->vehicle_type)->value('vehicle_type');
        $payment_method = DB::table('payment_methods')->where('id',$trip->payment_method)->value('payment');
        $booking_type = "";
        if($trip->booking_type == 1){
            $booking_type = "Instant";
        }else{
            $booking_type = "Ride Later";
        }
        if(is_object($trip)){
            return '<div class="col-md-6">
                <table class="table">
                <tbody><tr>
                  <th style="color:#000">Label</th>
                  <th style="color:#000">Value</th>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Trip ID</b></td>
                  <td style="color:	#808080">'.$trip->trip_id.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Customer name</b></td>
                  <td style="color:	#808080">'.$customer_name.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Trip type</b></td>
                  <td style="color:	#808080">'.$trip_type.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Booking type</b></td>
                  <td style="color:	#808080">'.$booking_type.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Driver name</b></td>
                  <td style="color:	#808080">'.$driver_name.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Status</b></td>
                  <td style="color:	#808080">'.$status.'</td>
                </tr>
                </tbody>
            </table>
            </div>
            
            <div class="col-md-6">
                <table class="table">
                <tbody><tr>
                  <th style="color:#000">Label</th>
                  <th style="color:#000">Value</th>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Pickup location</b></td>
                  <td style="color:	#808080">'.$trip->pickup_address.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Drop location</b></td>
                  <td style="color:	#808080">'.$trip->drop_address.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Vehicle Type</b></td>
                  <td style="color:	#808080">'.$vehicle_type.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Payment method</b></td>
                  <td style="color:	#808080">'.$payment_method.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Total</b></td>
                  <td style="color:	#808080">'.$trip->total.'</td>
                </tr>
                <tr>
                  <td style="color:	#808080"><b>Date & Time</b></td>
                  <td style="color:	#808080">'.$trip->pickup_date.'</td>
                </tr>
                </tbody>
            </table>
            </div>';
        }else{
            return '<h4 style="color:#000"><center>Sorry, invalid trip id..</center></h4>';
        }
        
    }
}
