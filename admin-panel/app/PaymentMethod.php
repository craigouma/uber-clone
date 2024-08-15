<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
     protected $fillable = [
        'id','payment','status','created_at','updated_at'
    ];
}
