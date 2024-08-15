<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwillioSetting extends Model
{
     protected $fillable = [
        'id','twillio_sid','twillio_auth_foken','twillio_number','created_at','updated_at'
    ];
}
