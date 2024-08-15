<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstantOffer extends Model
{
     protected $fillable = [
        'id','discount_type','discount','created_at','updated-at',
    ];
}
