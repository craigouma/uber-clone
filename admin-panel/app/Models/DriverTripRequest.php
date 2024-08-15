<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverTripRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'driver_id','trip_request_id','status','created_at', 'updated_at'
    ];
}
