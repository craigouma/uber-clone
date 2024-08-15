<?php

namespace App\Admin\Controllers;

use App\Models\Ratings;
use App\Customer;
use App\Driver;
use App\Trip;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RatingsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ratings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ratings());
        
        $grid->model()->orderBy('id','desc');
        $grid->column('id', __('Id'));
        $grid->column('trip_id', __('Trip Id'))->display(function($trips){
            $trip_id = Trip::where('id',$trips)->value('trip_id');
                return "$trip_id";
        });
        $grid->column('customer_id', __('Customer Id'))->display(function($customers){
            $first_name = Customer::where('id',$customers)->value('first_name');
                return "$first_name";
        });
        $grid->column('driver_id', __('Driver Id'))->display(function($drivers){
            $first_name = Driver::where('id',$drivers)->value('first_name');
                return "$first_name";
        });
        $grid->column('rating', __('Rating'));
        
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
           $trips = Trip::pluck('trip_id', 'id');
           $customers = Customer::pluck('first_name', 'id');
           $drivers = Driver::pluck('first_name', 'id');

           $filter->equal('trip_id', __('Trip id'))->select($trips);
           $filter->equal('customer_id', __('Customer id'))->select($customers);
           $filter->equal('driver_id', __('Driver id'))->select($drivers);     
           $filter->like('rating', __('Rating'));
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
        $form = new Form(new Ratings());
        $trips = Trip::pluck('trip_id', 'id');
        $customers = Customer::pluck('first_name', 'id');
        $drivers = Driver::pluck('first_name', 'id');
        
        $form->select('trip_id', __('Trip Id'))->options($trips)->rules(function ($form) {
            return 'required';
        });
        $form->select('customer_id', __('Customer Id'))->options($customers)->rules(function ($form) {
            return 'required';
        });
        $form->select('driver_id', __('Driver Id'))->options($drivers)->rules(function ($form) {
            return 'required';
        });
        $form->text('rating', __('Rating'))->required();
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
