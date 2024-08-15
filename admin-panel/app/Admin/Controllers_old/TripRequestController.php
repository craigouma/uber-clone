<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\VehicleCategory;
use App\PaymentMethod;
use App\PromoCode;
use App\Models\TripRequestStatus;
use App\Models\TripRequest;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TripRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Trip Request';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TripRequest());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer'))->display(function($customer_id){
            return Customer::where('id',$customer_id)->value('first_name');
        });
        $grid->column('distance', __('Distance'));
        $grid->column('vehicle_type', __('Vehicle type'))->display(function($vehicle_type){
            return VehicleCategory::where('id',$vehicle_type)->value('vehicle_type');
        });
        $grid->column('pickup_address', __('Pickup Address'));
        $grid->column('pickup_lat', __('Pickup Lat'))->hide();
        $grid->column('pickup_lng', __('Pickup Lng'))->hide();
        $grid->column('drop_address', __('Drop Address'));
        $grid->column('drop_lat', __('Drop Lat'))->hide();
        $grid->column('drop_lng', __('Drop Lng'))->hide();
        $grid->column('payment_method', __('Payment Method'))->display(function($payment_method){
            return PaymentMethod::where('id',$payment_method)->value('payment');
        });
        $grid->column('surge', __('Surge'));
        $grid->column('total', __('Total'));
        $grid->column('sub_total', __('Sub Total'));
        $grid->column('promo', __('Promo'))->display(function($promo_id){
            return PromoCode::where('id',$promo_id)->value('promo_code');
        });
        $grid->column('discount', __('Discount'));
        $grid->column('tax', __('Tax'));
        $grid->column('status', __('Status'))->display(function($status){
            $name = TripRequestStatus::where('id',$status)->value('status');
            
                return "$name";
            
       });
        //$grid->disableExport();
        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->disableExport();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        }
        
        $grid->filter(function ($filter) {
            //Get All status
            $statuses = TripRequestStatus::pluck('status', 'id');
            $promo_codes = PromoCode::pluck('promo_code', 'id');
            $payment_methods = PaymentMethod::pluck('payment', 'id');
            $customers = Customer::pluck('first_name', 'id');
            $vehicle_categories = VehicleCategory::pluck('vehicle_type', 'id');

            $filter->equal('customer_id', 'Customer')->select($customers);
            $filter->equal('payment_method', 'Payment Method ')->select($payment_methods);
            $filter->equal('vehicle_type', 'Vehicle Type')->select($vehicle_categories);
            $filter->equal('promo_id', 'Promo ')->select($promo_codes);
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
        $form = new Form(new TripRequest());

        $statuses = TripRequestStatus::pluck('status', 'id');
        $promo_codes = PromoCode::pluck('promo_code', 'id');
        $payment_methods = PaymentMethod::pluck('payment', 'id');
        $customers = Customer::pluck('first_name', 'id');
        $vehicle_categories = VehicleCategory::pluck('vehicle_type', 'id');


        $form->select('customer_id', __('Customer '))->options($customers)->rules(function ($form) {
            return 'required';
        });
        $form->decimal('distance', __('Distance'))->rules(function ($form) {
            return 'required';
        });
        $form->select('vehicle_type', __('Vehicle Type'))->options($vehicle_categories)->rules(function ($form) {
            return 'required';
        });
        $form->textarea('pickup_address', __('Pickup Address'))->rules(function ($form) {
            return 'required';
        });
        $form->text('pickup_lat', __('Pickup Lat'))->rules(function ($form) {
            return 'required';
        });
        $form->text('pickup_lng', __('Pickup Lng'))->rules(function ($form) {
            return 'required';
        });
        $form->textarea('drop_address', __('Drop Address'))->rules(function ($form) {
            return 'required';
        });
        $form->text('drop_lat', __('Drop Lat'))->rules(function ($form) {
            return 'required';
        });
        $form->text('drop_lng', __('Drop Lng'))->rules(function ($form) {
            return 'required';
        });
        $form->select('payment_method', __('Payment Method'))->options($payment_methods)->rules(function ($form) {
            return 'required';
        });
        $form->decimal('total', __('Total'))->rules(function ($form) {
            return 'required';
        });
        $form->decimal('sub_total', __('Sub Total'))->rules(function ($form) {
            return 'required';
        });
        $form->select('promo', __('Promo'))->options($promo_codes)->rules(function ($form) {
            return 'required';
        });
        $form->decimal('discount', __('Discount'))->rules(function ($form) {
            return 'required';
        });
        $form->decimal('tax', __('Tax'))->rules(function ($form) {
            return 'required';
        });
        $form->select('status', __('Status'))->options($statuses)->default(1)->rules(function ($form) {
            return 'required';
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
}
