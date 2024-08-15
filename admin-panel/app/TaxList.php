<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxList extends Model
{
     protected $fillable = [
        'id','tax_name','percent','status','created_at','updated_at'
    ];
}
