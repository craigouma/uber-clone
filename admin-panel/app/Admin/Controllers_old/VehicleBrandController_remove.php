<?php

namespace App\Admin\Controllers;

use App\VehicleBrand;
use App\VehicleCategory;
use App\VehicleType;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VehicleBrandController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vehicle Brand';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VehicleBrand);

        $grid->column('id', __('Id'));
        $grid->column('vehicle_category_id', __('Vehicle Category'))->display(function ($vehicle_category_id) {
            $value = VehicleCategory::find($vehicle_category_id)->category;
            if ($value == '') {
                return "-----";
            } else {
                return $value;
            }
        });
        $grid->column('vehicle_type_id', __('Vehicle Type'))->display(function ($vehicle_type_id) {
            $value = VehicleType::find($vehicle_type_id)->type;
            if ($value == '') {
                return "-----";
            } else {
                return $value;
            }
        });
        $grid->column('brand', __('Brand'));
        $grid->column('status', __('Status'))->display(function ($status) {
            $value = Status::find($status)->status;
            if ($status == 1) {
                return "<span class='label label-success'>$value</span>";
            } else {
                return "<span class='label label-danger'>$value</span>";
            }
        });
        
        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        }
        
        $grid->disableExport();
        $grid->filter(function ($filter) {
            //Get All status
            $statuses = Status::pluck('status', 'id');
            //Get All vehicle categories
            $vehicle_categories = VehicleCategory::pluck('category', 'id');
            //Get All vehicle categories
            $vehicle_types = VehicleType::pluck('type', 'id');

            $filter->equal('vehicle_category_id', 'Vehicle Category')->select($vehicle_categories);
            $filter->equal('vehicle_type_id', 'Vehicle Type')->select($vehicle_types);
            $filter->like('brand', 'Brand');
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
        //Get All status
        $statuses = Status::pluck('status', 'id');
        //Get All vehicle categories
        $vehicle_categories = VehicleCategory::pluck('category', 'id');

        $form = new Form(new VehicleBrand);

        $form->select('vehicle_category_id', __('Vehicle Category'))->options($vehicle_categories)->load('vehicle_type_id', '/admin/vehicle_types', 'id', 'type')->rules('required');
        $form->select('vehicle_type_id', "Vehicle Type")->options(function ($id) {
            $vehicle_type = VehicleType::find($id);

            if ($vehicle_type) {
                return [$vehicle_type->id => $vehicle_type->type];
            }
        })->rules('required');

        $form->text('brand', __('Brand'))->rules('required|max:100');
        $form->select('status', __('Status'))->options($statuses)->value(1);
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
