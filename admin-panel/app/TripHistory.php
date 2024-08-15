<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripHistory extends Model
{
     protected $fillable = [
        'id','trip_id','customer_id','driver_id','status','created_at','updated_at'
    ];
}
