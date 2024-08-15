<?php

namespace App\Admin\Controllers;

use App\Status;
use App\Models\DriverQuery;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverQueryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Queries';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverQuery());

        $grid->column('id', __('Id'));
        $grid->column('first_name', __('First Name'));
        $grid->column('email', __('Email'));
        $grid->column('last_name', __('Last Name'));
        $grid->column('phone_number', __('Phone Number'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });

            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
    
        
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            $filter->like('phone_number', 'Phone Number');
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
        $form = new Form(new DriverQuery());

        $statuses = Status::where('type','general')->pluck('name','id');

        $form->text('first_name', __('First Name'));
        $form->text('phone_number', __('Phone Number'));
        $form->select('status', __('Status'))->options($statuses)->rules('required');
        $form->email('email', __('Email'));
        $form->text('last_name', __('Last Name'));

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
