<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferType extends Model
{
     protected $fillable = [
        'id','type','created_at','updated_at'
    ];
}