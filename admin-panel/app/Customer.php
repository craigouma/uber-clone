<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'id', 'first_name', 'last_name', 'phone_number','email','email_verification_status','email_verification_code','password','profile_picture','status', 'country_code','phone_with_code', 'fcm_token', 'wallet','gender','email_verification_status','email_verification_code','overall_ratings','no_of_ratings','current_sub_id','subscription_trips','sub_purchased_at','sub_expired_at'
    ];
}
