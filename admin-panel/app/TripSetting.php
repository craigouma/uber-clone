<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripSetting extends Model
{
     protected $fillable = [
        'id','driver-commisson','maximum_searching-time','booking-searching_radius','created_at','updated_at'
    ];
}
