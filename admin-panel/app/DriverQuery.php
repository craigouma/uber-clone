<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverQuery extends Model
{
    protected $fillable = [
        'id', 'first_name','last_name','phone_number','email', 'description','status'
    ];
}
