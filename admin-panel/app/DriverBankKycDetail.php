<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverBankKycDetail extends Model
{
     protected $fillable = [
        'id','driver_id','bank_name','bank_account_number','ifsc_code','aadhar_number','pan_number','status','created_at','updated_at'
    ];
}
