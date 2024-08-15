<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSosContact extends Model
{
    protected $fillable = [
        'id','customer_id','name','phone_number','status'
    ];
}
