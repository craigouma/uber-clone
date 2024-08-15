<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripCancellation extends Model
{
    protected $fillable = [
        'id', 'trip_id', 'reason_id', 'cancelled_by', 'created_at','updated_at'
    ];
}
