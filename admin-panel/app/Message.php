<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
     protected $fillable = [
        'id','code','message','created_at','updated_at'
    ];
}
