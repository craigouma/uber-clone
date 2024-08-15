<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverCheckin extends Model
{
     protected $fillable = [
        'id','driver_id','checkin_time','checkout_time','totalhours','created_at','updated_at'
    ];
}
