<?php

namespace App\Admin\Controllers;

use App\Trip;
use App\Customer;
use App\Driver;
use App\PromoCode;
use App\DriverVehicle;
use App\PaymentMethod;
use App\BookingStatus;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TripController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Trip';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */ 
    protected function grid()
    {
        $grid = new Grid(new Trip());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('trip_id', __('Trip id'));
        $grid->column('customer_id', __('Customer name'))->display(function($customer){
            $customer_name = Customer::where('id',$customer)->value('first_name');
                return "$customer_name";
        });
        $grid->column('driver_id', __('Driver name'))->display(function($driver){
            $driver_name = Driver::where('id',$driver)->value('first_name');
                return "$driver_name";
        });
        $grid->column('pickup_date', __('Pickup date'))->hide();
        $grid->column('pickup_time', __('Pickup time'))->hide();
        $grid->column('pickup_location_address', __('Pickup location address'))->hide();
        $grid->column('drop_location_address', __('Drop location address'))->hide();
        $grid->column('pickup_location_lat', __('Pickup location latitude'))->hide();
        $grid->column('pickup_location_lng', __('Pickup location longitute'))->hide();
       
        $grid->column('vehicle_id', __('Vehicle number'))->display(function($vehicle){
            $vehicle_number = DriverVehicle::where('id',$vehicle)->value('vehicle_number');
                return "$vehicle_number";
        });
        $grid->column('payment_method', __('Payment method'))->display(function($payment){
            $payment_mode = PaymentMethod::where('id',$payment)->value('payment');
                return "$payment_mode";
        });
         $grid->column('promo_code', __('Promo code'))->display(function($promo){
            $promo_code = PromoCode::where('id',$promo)->value('promo_code');
                return "$promo_code";
        });
        $grid->column('surge', __('Surge'));
        $grid->column('sub_total', __('Sub total'));
        $grid->column('discount', __('Discount'));
        $grid->column('total', __('Total Fare'));
        $grid->column('otp', __('Otp'))->hide();
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = BookingStatus::where('id',$status)->value('status_name');
            
                return "$status_name";
            
        });
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
        });
        
        
        $grid->filter(function ($filter) {
         $statuses = BookingStatus::pluck('status_name','id');
         $customer = Customer::pluck('first_name', 'id');
         $driver = Driver::pluck('first_name', 'id');
         $payment_method = PaymentMethod::pluck('payment', 'id');
         $vehicle_number = DriverVehicle::pluck('vehicle_number', 'id');

            $filter->disableIdFilter();
            $filter->equal('customer_id', 'Customer Name')->select($customer);
            $filter->equal('driver_id', 'Driver Name')->select($driver);        
            $filter->equal('payment_method', 'Payment Method')->select($payment_method);
            $filter->equal('vehicle_id', 'Vehicle Number')->select($vehicle_number);
            $filter->date('pickup_date', 'Pickup Date');
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
        $form = new Form(new Trip());
         $drivers = Driver::pluck('first_name', 'id');
         $customer = Customer::pluck('first_name', 'id');
         $payment = PaymentMethod::pluck('payment', 'id');
         $promo_code = PromoCode::pluck('promo_code', 'id');
         $vehicle_number = DriverVehicle::pluck('vehicle_number', 'id');
         $statuses = BookingStatus::pluck('status_name','id');

        $form->text('trip_id', __('Trip id'));
        $form->select('customer_id', __('Customer name'))->options($customer)->rules(function ($form) {
            return 'required';
        });
        $form->select('driver_id', __('Driver name'))->options($drivers)->rules(function ($form) {
            return 'required';
        });
        $form->date('pickup_date', __('Pickup date'))->default(date('Y-m-d'))->rules(function ($form) {
            return 'required';
        });
        $form->time('pickup_time', __('Pickup time'))->default(date('H:i:s'))->rules(function ($form) {
            return 'required';
        });
        $form->textarea('pickup_address', __('Pickup address'))->rules(function ($form) {
            return 'required';
        });
        $form->textarea('drop_address', __('Drop  address'))->rules(function ($form) {
            return 'required';
        });
        $form->text('pickup_lat', __('Pickup latitude'))->rules(function ($form) {
            return 'required';
        });
        $form->text('pickup_lng', __('Pickup logitude'))->rules(function ($form) {
            return 'required';
        });
        $form->select('vehicle_id', __('Vehicle number'))->options($vehicle_number)->rules(function ($form) {
            return 'required';
        });
        $form->select('payment_method', __('Payment method'))->options($payment)->rules(function ($form) {
            return 'required';
        });
         $form->select('promo_code', __('Promo code'))->options($promo_code)->rules(function ($form) {
            return 'required';
        });
        $form->decimal('sub_total', __('Sub total'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->decimal('discount', __('Discount'));
        $form->decimal('total', __('Total Fare'))->rules(function ($form) {
            return 'required|max:100';
        });
       
        $form->text('otp', __('Otp'));
        $form->select('status', __('Status'))->options($statuses)->rules(function ($form) {
            return 'required';
        });

        return $form;
    }
}
