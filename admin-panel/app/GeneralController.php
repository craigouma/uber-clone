<?php

namespace App\Admin\Controllers;
use App\VehicleType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Controllers\ModelForm;

class GeneralController extends Controller
{
    use ModelForm;

    public function VehicleTypes()
    {
        return VehicleType::where('vehicle_category_id', $_GET['q'])->get(['id', DB::raw('type')]);
    }
}
