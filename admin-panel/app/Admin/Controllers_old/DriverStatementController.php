<?php

namespace App\Admin\Controllers;
use App\Driver;
use App\DriverStatement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverStatementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Statement';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverStatement());

        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver id'))->display(function($driver){
            $first_name = Driver::where('id',$driver)->value('first_name');
            return "$first_name";
        });
        $grid->column('total_rides', __('Total Rides'));
        $grid->column('total_earnings', __('Total Earnings'));
        $grid->column('commission', __('Commission'));
        $grid->column('commission_amount', __('Commission Amount'));
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
            //Get All status
            $drivers = Driver::pluck('first_name', 'id');
            $filter->eqval('driver', 'Driver')->select($drivers);
            $filter->like('total_rides', 'Total_Rides');
            $filter->like('total_earnings', 'Total_Earning');
            $filter->like('commission', 'Commission');
            $filter->like('commission_amount', 'Commission_Amount');
            
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
        $form = new Form(new DriverStatement());

         $drivers = Driver::pluck('first_name', 'id');
        $form->select('driver_id', __('Driver id'))->options($drivers)->rules(function ($form) {
            return 'required';
        });
        $form->text('total_rides', __('Total Rides'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->text('total_earnings', __('Total Earnings'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->text('commission', __('Commission'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->text('commission_amount', __('Commission Amount'))->rules(function ($form) {
            return 'required|max:100';
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
