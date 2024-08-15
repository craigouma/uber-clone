<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','user_id','receiver','message','file','file_name','created_at','updated_at', 'is_seen','is_admin'
    ];
}