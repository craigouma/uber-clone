<?php

namespace App\Admin\Controllers;

use App\Driver;
use App\DriverVehicle;
use App\AppSetting;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
class ViewDocumentController extends Controller
{
    public function index($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Driver Images');
            $content->description('View');
            $drivers = Driver::where('id',$id)->first();
            $vehicles = DriverVehicle::where('id',$id)->first();
            $app_setting = AppSetting::first();
            $data = array();
            
			$data['id_proof'] = $drivers->id_proof;
			$data['vehicle_image'] = $vehicles->vehicle_image;
			$data['vehicle_certificate'] = $vehicles->vehicle_certificate;
			$data['vehicle_insurance'] = $vehicles->vehicle_insurance;
            
            $content->body(view('admin.view_documents', $data));
        });

    }
}
