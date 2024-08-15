<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailContent extends Model
{
     protected $fillable = [
        'id','code','title','content','created-at','updated-at'
    ];
}
