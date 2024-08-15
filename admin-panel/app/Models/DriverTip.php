<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverTip extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'trip_id', 'driver_id', 'tip', 'tip_mode', 'created_at', 'updated_at'
    ];
}
