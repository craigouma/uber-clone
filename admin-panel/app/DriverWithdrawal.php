<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverWithdrawal extends Model
{
    protected $fillable = [
        'id', 'driver_id', 'amount','reference_proof','reference_no','status'
    ];
}
