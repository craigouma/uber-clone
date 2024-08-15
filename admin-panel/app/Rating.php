<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
     protected $fillable = [
        'id','trip_id','customer_id','driver_id','rating','feedback','created_at','updated_at'
    ];
}
