<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'id', 'first_name','last_name','phone_number','phone_with_code','email','password','date_of_birth','licence_number','fcm_token','overall_ratings', 'no_of_ratings','shared_ride_status','status','id_proof_status','referral_code','refered_by','driver_hiring_status','wallet'
    ];
}
