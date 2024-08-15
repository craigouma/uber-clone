<?php

namespace App\Admin\Controllers;

use App\Models\DriverTip;
use App\Customer;
use App\Driver;
use App\PaymentMethod;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverTipController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Tips';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverTip());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer'))->display(function($customer_id){
            $customer_name = Customer::where('id',$customer_id)->value('first_name');
            return $customer_name;
        });
        $grid->column('driver_id', __('Driver'))->display(function($driver_id){
            $driver_name = Driver::where('id',$driver_id)->value('first_name');
            return $driver_name;
        });
        $grid->column('tip_mode', __('Tip Mode'))->display(function($tip_mode){
            $driver_name = PaymentMethod::where('id',$tip_mode)->value('payment');
            return $driver_name; 
        });
        $grid->column('tip', __('Tip'))->display(function($tip){
            return $tip;
        });
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableExport();
        
        $grid->filter(function ($filter) {
            $customers = Customer::pluck('first_name', 'id');
            $drivers = Driver::pluck('first_name', 'id');
            
            $filter->equal('customer_id', 'Customer')->select($customers);
            $filter->equal('driver_id', 'Drivers')->select($drivers);
            
        });

        return $grid;
    }
}
