<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPromoHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'promo_id',
    ];
}
