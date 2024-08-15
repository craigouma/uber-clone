<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerWalletHistory extends Model
{
    protected $fillable = [
        'id', 'customer_id','type', 'message','message_ar', 'amount', 'transaction_type', 'created_at', 'updated_at'
    ];
}
