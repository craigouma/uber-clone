<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintSubCategory extends Model
{
     protected $fillable = [
        'id','complaint_category','complaint_sub_category_name','status','created_at','updated_at'
    ];
}
