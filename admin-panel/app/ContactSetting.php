<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
     protected $fillable = [
        'id','phone-number','email','address','lat','lng','created_at','updated_at'
    ];
}
