<?php

namespace App\Admin\Controllers;
use App\Trip;
use App\Customer;
use App\Driver;
use App\DriverVehicle;
use App\PaymentMethod;
use App\Models\DriverHiringStatus;
use App\Models\DriverHiringRequest;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverHiringRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Hiring Requests';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverHiringRequest());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer Id'))->display(function($customer){
            $customer_name = Customer::where('id',$customer)->value('first_name');
                return "$customer_name";
        });
        $grid->column('driver_id', __('Driver Id'))->display(function($driver){
            $driver_name = Driver::where('id',$driver)->value('first_name');
                return "$driver_name";
        });
        $grid->column('pickup_location', __('Pickup location'));
        $grid->column('pickup_lat', __('Pickup lat'))->hide();
        $grid->column('pickup_lng', __('Pickup lng'))->hide();
        $grid->column('drop_location', __('Drop location'));
        $grid->column('drop_lat', __('Drop lat'))->hide();
        $grid->column('drop_lng', __('Drop lng'))->hide();
        $grid->column('pickup_date', __('Pickup date'));
        $grid->column('pickup_time', __('Pickup time'));
        $grid->column('drop_date', __('Drop date'));
        $grid->column('drop_time', __('Drop time'));
        $grid->column('payment_method', __('Payment Method'))->display(function($payment){
            $payment_mode = PaymentMethod::where('id',$payment)->value('payment');
                return "$payment_mode";
        });
        $grid->column('total', __('Total'));
        $grid->column('tax', __('Tax'))->hide();
        $grid->column('sub_total', __('Sub total'))->hide();
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = DriverHiringStatus::where('id',$status)->value('status_name');
            return "$status_name";
        });;

        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
        }
        
        $grid->filter(function ($filter) {
         $statuses = DriverHiringStatus::pluck('status_name','id');
         $customer = Customer::pluck('first_name', 'id');
         $driver = Driver::pluck('first_name', 'id');
         $payment_method = PaymentMethod::pluck('payment', 'id');
            $filter->disableIdFilter();
            $filter->equal('customer_id', 'Customer Name')->select($customer);
            $filter->equal('driver_id', 'Driver Name')->select($driver);        
            $filter->equal('payment_method', 'Payment Method')->select($payment_method);
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
        $form = new Form(new DriverHiringRequest());

        $form->number('customer_id', __('Customer id'));
        $form->number('driver_id', __('Driver id'));
        $form->textarea('pickup_location', __('Pickup location'));
        $form->text('pickup_lat', __('Pickup lat'));
        $form->text('pickup_lng', __('Pickup lng'));
        $form->textarea('drop_location', __('Drop location'));
        $form->text('drop_lat', __('Drop lat'));
        $form->text('drop_lng', __('Drop lng'));
        $form->date('pickup_date', __('Pickup date'))->default(date('Y-m-d'));
        $form->time('pickup_time', __('Pickup time'))->default(date('H:i:s'));
        $form->date('drop_date', __('Drop date'))->default(date('Y-m-d'));
        $form->time('drop_time', __('Drop time'))->default(date('H:i:s'));
        $form->decimal('total', __('Total'));
        $form->decimal('tax', __('Tax'));
        $form->decimal('sub_total', __('Sub total'));
        $form->number('status', __('Status'));

        return $form;
    }
}
