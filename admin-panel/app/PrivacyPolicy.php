<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicy extends Model
{
     protected $fillable = [
        'id','title','description','status','created_at','updated_at'
    ];
}
