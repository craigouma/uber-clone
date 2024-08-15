<?php

namespace App\Admin\Controllers;

use App\VehicleCategory;
use App\Models\VehicleSlug;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VehicleCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vehicle Categories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VehicleCategory);

        $grid->column('id', __('Id'));
        $grid->column('vehicle_type', __('Vehicle type'));
        $grid->column('vehicle_mode', __('Vehicle Mode'))->display(function($vehicle_mode){
            $vehicle_mode = Status::where('id',$vehicle_mode)->value('name');
                return "$vehicle_mode";
        });
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
        });
        
        
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
        
            $filter->disableIdFilter();
            $filter->like('vehicle_type', 'vehicle type');
            $filter->equal('base_fare', 'Base fare');
            $filter->equal('price_per_km', 'Price per km');
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
        $form = new Form(new VehicleCategory);
        $statuses = Status::where('type','general')->pluck('name','id');
        $vehicle_mode = Status::where('type','vehicle_mode')->pluck('name','id');
        $vehicle_slugs = VehicleSlug::pluck('slug','id');
        
        $form->text('vehicle_type', __('Vehicle type'))->rules('required|max:250');
        $form->select('vehicle_mode', __('Vehicle Mode'))->options($vehicle_mode)->rules('required');
        $form->select('vehicle_slug', __('Vehicle Slug'))->options($vehicle_slugs)->rules('required');
        $form->textarea('description', __('Description'))->rules('required');
        $form->text('vehicle_type_ar', __('Vehicle Type Arabic'));
        $form->textarea('description_ar', __('Description Arabic'));
        $form->image('active_icon', __('Active Icon'))->move('vehicle_categories/')->uniquename()->rules('required');
        $form->image('inactive_icon', __('InActive Icon'))->move('vehicle_categories/')->uniquename()->rules('required');
        $form->select('status', __('Status'))->options($statuses)->rules('required');

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
