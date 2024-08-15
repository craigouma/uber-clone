<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripRequest extends Model
{
protected $fillable = [
        'id','driver_id','customer_id','distance','trip_type','booking_type','vehicle_type','pickup_address','pickup_date','pickup_lat','pickup_lng','drop_address','drop_lat','drop_lng','payment_method','total','sub_total','discount','tax','promo','status','created_at','updated_at','package_id','static_map','trip_sub_type','is_multiple_drops','zone','surge'
    ];
}
