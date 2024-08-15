<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedTripSetting extends Model
{
    protected $fillable = [
        'pickup_radius',
        'drop_radius',
        'max_bookings',
    ];

   
}
