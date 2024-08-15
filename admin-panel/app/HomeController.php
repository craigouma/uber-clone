<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Country;
use App\Driver;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Facades\Admin;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $data = array();
            $current_year = date("Y");
            $data['customers'] = Customer::where('status','!=',0)->count();
            $data['drivers'] = Driver::where('status','!=',0)->count();
            $data['countries'] = Country::where('status','!=',0)->count();

            

            $content->body(view('admin.dashboard', $data));
        });

    }
}
