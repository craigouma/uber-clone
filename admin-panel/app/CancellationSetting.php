<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancellationSetting extends Model
{
     protected $fillable = [
        'id','no_of_free','cancellation_charge','created_at','updated_at'
    ];
}
