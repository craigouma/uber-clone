<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverRecharge extends Model
{
     protected $fillable = [
        'id','driver_id','amount','created_at','updated_at'
    ];
}
