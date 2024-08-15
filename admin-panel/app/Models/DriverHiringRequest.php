<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverHiringRequest extends Model
{
    use HasFactory;
     protected $fillable = [
        'customer_id', 'driver_id', 'pickup_location', 'pickup_date', 'pickup_time', 'pickup_lat', 'pickup_lng', 'drop_location', 'drop_date', 'drop_time', 'drop_lat', 'drop_lng',
            'zone','tax','sub_total','total','payment_method','status'
    ];
    
    
}
