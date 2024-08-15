<?php

namespace App\Admin\Controllers;

use App\VehicleCategory;
use App\Status;
use App\Models\FareManagement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FareManagementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Fare Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FareManagement());

        $grid->column('id', __('Id'));
        $grid->column('vehicle_type', __('Vehicle Type'))->display(function($vehicle_type){
            return VehicleCategory::where('id',$vehicle_type)->value('vehicle_type');
        });
        $grid->column('fare_type', __('Fare Type'))->display(function($fare_type){
            if ($fare_type == 1) {
                return "<span class='label label-success'>Instant Booking</span>";
            } if ($fare_type == 2) {
                return "<span class='label label-info'> OutStation</span>";
            }
            });
        $grid->column('base_fare', __('Base Fare'));
        $grid->column('price_per_km', __('Price Per Km'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
                $actions->disableView();
        });
        
        
        $grid->filter(function ($filter) {
            $statuses = Status::pluck('name','id'); 
            $vehicle_categories = VehicleCategory::pluck('vehicle_type', 'id');

            $filter->equal('vehicle_type', 'Vehicle Type')->select($vehicle_categories);
            $filter->equal('fare_type', 'Fare Type')->select([1 => 'Instant Booking', 2 => 'OutStation']);        
            $filter->like('base_fare', 'Base Fare');        
            $filter->like('price_per_km', 'Price Per Km');
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
        $form = new Form(new FareManagement());

        $statuses = Status::pluck('name','id'); 
        $vehicle_categories = VehicleCategory::pluck('vehicle_type', 'id');

        $form->select('vehicle_type', __('Vehicle Type'))->options($vehicle_categories)->rules(function ($form) {
                    return 'required';
                });
        $form->select('fare_type', __('Fare Type'))->options([1 => 'Instant Booking', 2 => 'OutStation'])->rules(function ($form) {
            return 'required';
        });
        $form->decimal('base_fare', __('Base Fare'))->rules(function ($form) {
                    return 'required';
                });
        $form->number('price_per_km', __('Price Per Km'))->rules(function ($form) {
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
