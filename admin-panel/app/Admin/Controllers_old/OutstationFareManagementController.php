<?php

namespace App\Admin\Controllers;

use App\VehicleCategory;
use App\Status;
use App\Models\OutstationFareManagement;
use App\Models\TripSubType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OutstationFareManagementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Outstation Fare Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OutstationFareManagement());

        $grid->column('id', __('Id'));
        $grid->column('vehicle_type', __('Vehicle type'))->display(function($vehicle_type){
            return VehicleCategory::where('id',$vehicle_type)->value('vehicle_type');
        });
        $grid->column('trip_sub_type_id', __('Trip Sub Type'))->display(function($trip_sub_type){
            return TripSubType::where('id',$trip_sub_type)->value('trip_sub_type');
        });
        $grid->column('base_fare', __('Base fare'));
        $grid->column('price_per_km', __('Price per km'));
        $grid->column('driver_allowance', __('Driver allowance'));
        $grid->column('status', __('Status'))->display(function($status){
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
                $actions->disableDelete();
            });
        }
        
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            $vehiclecategories = VehicleCategory::pluck('vehicle_type', 'id');
         
            $filter->equal('vehicle_type', 'Vehicle Type')->select($vehiclecategories);
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
        $form = new Form(new OutstationFareManagement());

        $statuses = Status::where('type','general')->pluck('name','id');
        $vehicle_categories = VehicleCategory::where('vehicle_mode',18)->pluck('vehicle_type', 'id');
        $trip_sub_type = TripSubType::where('trip_type','3')->pluck('trip_sub_type', 'id');
       
        $form->select('vehicle_type', 'Vehicle Type')->options($vehicle_categories)->rules(function ($form) {
                return 'required';
        });
        $form->select('trip_sub_type_id', __('Trip Sub Type'))->options($trip_sub_type)->rules('required');
        $form->decimal('base_fare', __('Base fare'))->rules('required');
        $form->decimal('price_per_km', __('Price per km'))->rules('required');
        $form->decimal('driver_allowance', __('Driver allowance'))->rules('required');
        $form->select('status', __('Status'))->default(1)->options($statuses)->rules('required');
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
