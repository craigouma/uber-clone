<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancellationReason extends Model
{
     protected $fillable = [
        'id','reason','type','created_at','updated_at'
    ];
}
