<?php

namespace App\Admin\Controllers;

use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StatusController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Status';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Status());

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'));
        $grid->column('name', __('Name'));

        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            //Get All status
            $statuses = Status::pluck('name', 'id');
            $filter->equal('name', 'Name')->select($statuses);
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
        $form = new Form(new Status());

        $form->text('type', __('Type'))->required();
        $form->text('name', __('Status Name'))->required();
        $form->text('name_ar', __('Status Name Arabic'));
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
