<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeatureSetting extends Model
{
     protected $fillable = [
        'id','enable_sms','enable_referral_module','cancellation_fee','contact_showing','created_at','updated_at'
    ];
}
