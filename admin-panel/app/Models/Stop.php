<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id','trip_request_id', 'trip_id', 'address', 'latitude', 'longitude','status'
    ];
}
