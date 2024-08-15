<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscriptionHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'sub_id', 'purchased_at', 'expiry_at','created_at', 'updated_at'
    ];
}
