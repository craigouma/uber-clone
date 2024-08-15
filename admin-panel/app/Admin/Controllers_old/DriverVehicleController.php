<?php

namespace App\Admin\Controllers;

use App\DriverVehicle;
use App\Status;
use App\Driver;
use App\VehicleCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\DB;
class DriverVehicleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Vehicles';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverVehicle);

        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver'))->display(function($drivers){
            $driver_name = Driver::where('id',$drivers)->value('first_name');
                return "$driver_name";
        });
        $grid->column('vehicle_type', __('Vehicle type'))->display(function(){
            $value = VehicleCategory::where('id',$this->vehicle_type)->value('vehicle_type');
            return $value;
        });
        $grid->column('brand', __('Brand'));
        $grid->column('color', __('Color'));
        $grid->column('vehicle_name', __('Vehicle name'));
        $grid->column('vehicle_number', __('Vehicle number'));
        $grid->column('vehicle_image_status', __('Vehicle Image Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 14) {
                return "<span class='label label-warning'>$status_name</span>";
            } if ($status == 15) {
                return "<span class='label label-info'>$status_name</span>";
            }if ($status == 16) {
                return "<span class='label label-success'>$status_name</span>";
            }if ($status == 17) {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('vehicle_certificate_status', __('Vehicle Certificate Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 14) {
                return "<span class='label label-warning'>$status_name</span>";
            } if ($status == 15) {
                return "<span class='label label-info'>$status_name</span>";
            }if ($status == 16) {
                return "<span class='label label-success'>$status_name</span>";
            }if ($status == 17) {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('vehicle_insurance_status', __('Vehicle Insurance Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 14) {
                return "<span class='label label-warning'>$status_name</span>";
            } if ($status == 15) {
                return "<span class='label label-info'>$status_name</span>";
            }if ($status == 16) {
                return "<span class='label label-success'>$status_name</span>";
            }if ($status == 17) {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('status', __('status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        
        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        }
        
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            $vehicle_categories= VehicleCategory::pluck('vehicle_type','id');
            $drivers = Driver::pluck('first_name', 'id');

            $filter->disableIdFilter();
            $filter->equal('driver_id', 'Driver');
            $filter->like('vehicle_type', 'Vehicle type')->select($vehicle_categories);
            $filter->like('brand', 'Brand');        
            $filter->like('color', 'Color');        
            $filter->like('vehicle_name', 'Vehicle name');
            $filter->equal('vehicle_number', 'vehicle_number');
            $filter->equal('vehicle_image_status', 'Vehicle Image Upload Status')->select([14 => 'Wainting For Upload', 15 => 'Waiting For Approval', 16 => 'Approved', 17 => 'Rejected']);
            $filter->equal('vehicle_certificate_status', 'Vehicle Certificate Upload Status')->select([14 => 'Wainting For Upload', 15 => 'Waiting For Approval', 16 => 'Approved', 17 => 'Rejected']);
            $filter->equal('vehicle_insurance_status', 'Vehicle Insurance Upload Status')->select([14 => 'Wainting For Upload', 15 => 'Waiting For Approval', 16 => 'Approved', 17 => 'Rejected']);
            $filter->equal('status', 'Status')->select($statuses);
        
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DriverVehicle);
        $statuses = Status::where('type','general')->pluck('name','id');
        $image_statuses = Status::where('type','verification_status')->pluck('name','id');
        $vehicle_categories= VehicleCategory::pluck('vehicle_type','id');
        $drivers = Driver::pluck('first_name', 'id');
        
        $form->select('driver_id', __('Driver'))->load('vehicle_type', '/admin/get-vehicle-category', 'id', 'vehicle_type')->options(function ($id) {
            $driver = Driver::find($id);

            if ($driver) {
                return [$driver->id => $driver->first_name];
            }
        })->rules(function ($form) {
            return 'required';
        });
        $form->select('vehicle_type', __('Vehicle type'))->options(function ($id) {
            $vehicle_type = VehicleCategory::find($id);

            if ($vehicle_type) {
                return [$vehicle_type->id => $vehicle_type->vehicle_type];
            }
        })->rules(function ($form) {
            return 'required';
        });
        $form->text('brand', __('Brand'))->rules('required|max:250');
        $form->text('color', __('Color'))->rules('required|max:250');
        $form->text('vehicle_name', __('Vehicle Name'))->rules('required|max:250');
        $form->text('vehicle_number', __('Vehicle Number'))->rules('required|max:250');
        $form->image('vehicle_image', __('Vehicle Image'))->uniqueName()->move('drivers/vehicle_documents')->rules('required');
        $form->image('vehicle_certificate', __('Vehicle Certificate'))->uniqueName()->move('drivers/vehicle_documents')->rules('required');
        $form->image('vehicle_insurance', __('Vehicle Insurance'))->uniqueName()->move('drivers/vehicle_documents')->rules('required');
        $form->select('vehicle_image_status', __('Vehicle Image Status'))->default(15)->options($image_statuses)->rules('required');
        $form->select('vehicle_certificate_status', __('Vehicle Certificate Status'))->default(15)->options($image_statuses)->rules('required');
        $form->select('vehicle_insurance_status', __('Vehicle Insurance Status'))->default(15)->options($image_statuses)->rules('required');
        $form->select('status', __('status'))->options($statuses)->default(1)->rules('required');
        
        $form->saved(function (Form $form) {
            $this->update_status($form->model()->vehicle_type,$form->model()->driver_id);
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete(); 
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        
        return $form;
    }
    
    public function update_status($vehicle_type,$driver_id){
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DB'));
        $database = $factory->createDatabase();
        
        //Booking Details
        $booking['booking_status'] = 0;
        $booking['pickup_address'] = "";
        $booking['drop_address'] = "";
        $booking['static_map'] = "";
        $booking['total'] = 0;
        $booking['booking_id'] = 0;
        $booking['customer_name'] = "";
        $booking['trip_type'] = "";
        $booking['trip_type_ar'] = "";
        
        //Location Details
        $geo['lat'] = 0;
        $geo['lng'] = 0;
        $geo['bearing'] = 0;
        
        $driver = Driver::where('id',$driver_id)->first();
        $newPost = $database
        ->getReference('drivers/'.$vehicle_type.'/'.$driver_id)
        ->update([
            'driver_id' => $driver_id,
            'vehicle_type' => $vehicle_type,
            'vehicle_mode' => VehicleCategory::where('id',$vehicle_type)->value('vehicle_mode'),
            'vehicle_slug' => DB::table('vehicle_slugs')->where('id',VehicleCategory::where('id',$vehicle_type)->value('vehicle_slug'))->value('slug'),
            'driver_name' => $driver->first_name,
            'online_status' => 0,
            'geo' => $geo,
            'booking' => $booking
        ]);
        
        $newPost = $database
        ->getReference('shared/'.$driver_id)
        ->update([
            'booking_id' => 0,
            'pickup_address' => '',
            'drop_address' => '',
            'total' => 0,
            'customer_name' => ''
        ]);
    }
}
