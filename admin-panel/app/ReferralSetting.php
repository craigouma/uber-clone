<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralSetting extends Model
{
     protected $fillable = [
        'id','referral_message','referral-bonus','created_at','updated_at'
    ];
}
