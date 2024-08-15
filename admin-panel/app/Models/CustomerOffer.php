<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','customer_id','title','description','image','view_status','type','status','ref_id','created_at','updated-at',
    ];
}
