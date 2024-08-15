<?php

namespace App\Admin\Controllers;

use App\Models\Package;
use App\Status;
use App\VehicleCategory;
use App\Models\RentalFareManagement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RentalFareManagementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Rental Fare Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RentalFareManagement());

        $grid->column('id', __('Id'));
        $grid->column('vehicle_type', __('Vehicle Type'))->display(function($vehicle_type){
            return VehicleCategory::where('id',$vehicle_type)->value('vehicle_type');
        });
        $grid->column('package_id', __('Package Id'))->display(function($package_id){
            return Package::where('id',$package_id)->value('package_name');
        });
        $grid->column('price_per_km', __('Price Per Km'));
        $grid->column('price_per_hour', __('Price Per Hour'));
        $grid->column('package_price', __('Package Price'));
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
            $packages = Package::pluck('package_name', 'id');

            $filter->equal('package_id', 'Package Id')->select($packages);
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
        $form = new Form(new RentalFareManagement());

        $statuses = Status::where('type','general')->pluck('name','id');
        $vehicle_categories = VehicleCategory::where('vehicle_mode',18)->pluck('vehicle_type', 'id');
        $packages = Package::pluck('package_name', 'id');
        
        $form->select('vehicle_type', 'Vehicle Type')->options($vehicle_categories)->rules(function ($form) {
                return 'required';
        });
        $form->select('package_id', __('Package Id'))->options($packages)->rules('required');
        $form->decimal('price_per_km', __('Price Per Km'))->rules('required');
        $form->decimal('price_per_hour', __('Price Per Hour'))->rules('required');
        $form->decimal('package_price', __('Package Price'))->rules('required');
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
