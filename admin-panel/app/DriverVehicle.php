<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
     protected $fillable = [
        'id','driver_id','vehicle_type','brand','color','vehicle_name','vehicle_number','status','created_at','updated_at','vehicle_image_status'
    ];
}
