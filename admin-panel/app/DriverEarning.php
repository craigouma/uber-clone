<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverEarning extends Model
{
    protected $fillable = [
        'id', 'trip_id', 'order_id', 'driver_id','amount','created_at','updated_at'
    ];
}
