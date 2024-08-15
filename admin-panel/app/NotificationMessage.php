<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
     protected $fillable = [
        'id','type','message','image','status','created_at','updated_at'
    ];
}
