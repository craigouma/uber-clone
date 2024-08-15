<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintCategory extends Model
{
     protected $fillable = [
        'id','complaint_category_name','status','created_at','updated_at'
    ];
}
