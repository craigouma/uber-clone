<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
     protected $fillable = [
        'id','trip_id','mode','amount','created_at','updated_at'
    ];
}
