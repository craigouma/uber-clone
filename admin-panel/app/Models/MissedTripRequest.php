<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissedTripRequest extends Model
{
    use HasFactory;
    public function getLatitudeAttribute($value)
    {
        return (double) $value;
    }
    
    public function getLongitudeAttribute($value)
    {
        return (double) $value;
    }
    
    public function getWeightAttribute($value)
    {
        return (double) $value;
    }
    
    protected $fillable = [
        'customer_id', 'zone', 'latitude', 'longitude','weight', 'created_at', 'updated_at'
    ];
}
