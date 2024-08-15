<?php

namespace App\Admin\Controllers;

use App\Status;
use App\Models\TripSubType;
use App\Models\TripType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TripSubTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Trip Sub Types';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TripSubType());

        $grid->column('id', __('Id'));
        $grid->column('trip_type', __('Trip Type'))->display(function($trip_type){
            $trip_type = TripType::where('id',$trip_type)->value('name');
                return "$trip_type";
        });
        $grid->column('trip_sub_type', __('Trip Sub Type'));
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            
            $filter->like('name', 'Name');
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
        $form = new Form(new TripSubType());

         $trip_type = TripType::pluck('name','id');

        $form->select('trip_type', __('Trip Type'))->options($trip_type)->rules('required');
        $form->text('trip_sub_type', __('Trip sub Type'))->rules('required');
        $form->text('trip_sub_type_ar', __('Trip sub Type Arabic'));
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
