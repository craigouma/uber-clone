<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PromoCode;

class PromoCodeController extends Controller
{
    public function promo()
    {
        $data = PromoCode::where('status',1)->get();
        
        return response()->json([
            "result" => $data,
            "count" => count($data),
            "message" => 'Success',
            "status" => 1
        ]);
    }
}
