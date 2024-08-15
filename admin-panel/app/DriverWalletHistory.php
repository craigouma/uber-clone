<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverWalletHistory extends Model
{
    protected $fillable = [
        'id', 'driver_id', 'type','message','message_ar','amount','created_at','updated_at'
    ];
}
