<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
     protected $fillable = [
        'id','trip_id','customer_id','booking_type','driver_id','pickup_date','start_time','end_time','pickup_address','pickup_lat','pickup_lng','drop_address','drop_lat','drop_lng','distance','vehicle_id','payment_method','total','sub_total','discount','tax','promo_code','otp','ratings','status','created_at','updated_at','actual_pickup_address','actual_pickup_lat','actual_pickup_lng','actual_drop_address','actual_drop_lat','actual_drop_lng','vehicle_type','trip_type','package_id','trip_sub_type','is_multiple_drops','customer_rating','tip','zone','static_map','is_subscription_trip','surge'
    ];
}
