<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
     protected $fillable = [
        'id','trip_id','customer_id','driver_id','subject','complaint_category','complaint_sub_category','description','status','created_at','updated_at'
    ];
}
