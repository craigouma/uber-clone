<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Trip;
use App\VehicleCategory;
use App\Driver;
use App\AppSetting;
use App\Models\Zone;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $data = array();
            $current_year = date("Y");
            $data['customers'] = Customer::where('status','!=',0)->count();
            $data['total_orders'] = Trip::count();
            $data['completed_orders'] = Trip::where('status','=',5)->count();
            $data['drivers'] = Driver::where('status','!=',0)->count();

            $customers = Customer::select('id', 'created_at')
                ->get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->created_at)->format('M');
                });
            $orders = Trip::select('id', 'created_at')
                ->get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->created_at)->format('M');
                });
            $month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $temp = [];
            foreach ($customers as $c) {
                $temp[Carbon::parse($c[0]->created_at)->format('M')] = count($c);
            }
            $growth = [];
            foreach ($month as $m) {
                if (isset($temp[$m])) {
                    $growth[] = $temp[$m];
                } else {
                    $growth[] = 0;
                }

            }
            $temp_orders = [];
            foreach ($orders as $o) {
                $temp_orders[Carbon::parse($o[0]->created_at)->format('M')] = count($o);
            }
            $growth_orders = [];
            foreach ($month as $m) {
                if (isset($temp_orders[$m])) {
                    $growth_orders[] = $temp_orders[$m];
                } else {
                    $growth_orders[] = 0;
                }

            }
            $data['customers_chart'] = implode(",", $growth);
            $data['orders_chart'] = implode(",", $growth_orders);

            $content->body(view('admin.dashboard', $data));
        });

    }
    
    public function tracking(){
        
        return Admin::content(function (Content $content) {
            $content->header('Live Tracking');
            $default_country = AppSetting::where('id',1)->value('default_country');
            $countries = VehicleCategory::where('status',1)->where('country_id',$default_country)->get();
            $zones = Zone::where('country_id',$default_country)->get();
            $path = [];
            $i = 0;
            foreach($countries as $key => $value){
                foreach($zones as $key1 => $value1){
                    $path[$i] = $default_country.'/drivers/'.$value->id.'/'.$value1->id;
                    $i++;
                }
            }
            $data['path'] = json_encode($path);
            $data['path'] = preg_replace("_\\\_", "\\\\\\", $data['path']);
            $data['path'] = preg_replace("/\"/", "\\\"", $data['path']);
            
            $content->body(view('admin.tracking', $data));
        });
    }
    
    public function dispatch_panel(){
        return Admin::content(function (Content $content) {
            $content->header('Dispatch System');
    
            $data['total_drivers'] = Driver::where('status',1)->count();
            $data['active_drivers'] = Driver::where('online_status',1)->count();
            $data['total_trips'] = Trip::where('status','<=',5)->count();
            $data['total_customers'] = Customer::where('status',1)->count();
            
            $content->body(view('admin.dispatch', $data));
        });
    }

    public function live_chat(){   
        return Admin::content(function (Content $content) {
            $data['users'] = Customer::where('status', 1)->orderBy('id', 'DESC')->get();
            $data['messages'] = null;
            $content->header('Customers Chat');
            $content->body(view('admin.chat', $data));
        });
    }
    
    public function create_zone($id){
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Draw Zone');
            $data['id'] = $id;
            $row = DB::table('app_settings')->first();
            $data['capital_lat'] = $row->capital_lat;
            $data['capital_lng'] = $row->capital_lng;
            $content->body(view('zones.create_zones', $data));
        });
    }

    public function view_zone($id){
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Draw Zone');
            $data['id'] = $id;
            $row = DB::table('app_settings')->first();
            $data['capital_lat'] = $row->capital_lat;
            $data['capital_lng'] = $row->capital_lng;
            $polygon = Zone::where('id',$id)->value('polygon');
            $polygon = explode(";",$polygon);
            $data['polygon'] = [];
            foreach($polygon as $key => $value){
               $value = explode(",",$value);
               if(@$value[1]){
                $data['polygon'][$key]['lat'] = floatval($value[0]);
                $data['polygon'][$key]['lng'] = floatval($value[1]);
               }
            }

            $data['polygon'] = json_encode($data['polygon'],TRUE);
            $content->body(view('zones.view_service_zones', $data));
        });
    }

}
