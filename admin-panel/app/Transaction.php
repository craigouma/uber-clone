<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
     protected $fillable = [
        'id','customer_id','amount','payment_method','type','created_at','updated_at'
    ];
}
