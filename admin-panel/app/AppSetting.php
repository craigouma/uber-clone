<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
     protected $fillable = [
        'id','app_name','logo','app_version','default_currency','default_currency_symbol','login_image','subscription_status','created_at','updated-at',
    ];
}
