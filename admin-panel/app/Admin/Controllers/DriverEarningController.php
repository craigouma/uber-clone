<?php

namespace App\Admin\Controllers;

use App\DriverEarning;
use App\Trip;
use App\Driver;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverEarningController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Earnings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverEarning);

        $grid->column('id', __('Id'));
        $grid->column('trip_id', __('Trip id'))->display(function($trip_id){
            return Trip::where('id',$trip_id)->value('trip_id');
        });
        $grid->column('driver_id', __('Driver name'))->display(function($vendor_id){
            return Driver::where('id',$vendor_id)->value('first_name');
        });
        $grid->column('amount', __('Amount'));
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreation();
        
        $grid->filter(function ($filter) {
            //Get All status
            $trips = Trip::pluck('trip_id', 'id');
            $drivers = Driver::pluck('first_name', 'id');
            $filter->like('trip_id', 'Trip')->select($trips);
            $filter->like('driver_id', 'Driver')->select($drivers);
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
        $form = new Form(new DriverEarning);
        $driver = Driver::pluck('first_name', 'id');
        $trip = Trip::pluck('trip_id', 'id');

        $form->select('trip_id', __('Trip id'))->options($trip)->rules(function ($form) {
            return 'required';
        });
        $form->select('driver_id', __('Driver name'))->options($driver)->rules(function ($form) {
            return 'required';
        });
        $form->decimal('amount', __('Amount'))->rules(function ($form) {
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
