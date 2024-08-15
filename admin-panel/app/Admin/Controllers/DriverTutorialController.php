<?php

namespace App\Admin\Controllers;

use App\DriverTutorial;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverTutorialController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Tutorial';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverTutorial);

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('thumbnail_image', __('Thumbnail Image'))->image();
        $grid->column('status', __('Status'))->display(function ($status) {
            $status_name = Status::where('id', $status)->value('name');
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
            $actions->disableEdit();
        });


        $grid->filter(function ($filter) {
            //Get All status
            $filter->equal('status', 'Status');
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
        $form = new Form(new DriverTutorial);

        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->text('title_ar', __('Title Arabic'));
        $form->textarea('description_ar', __('Description Arabic'));
        $form->image('thumbnail_image', __('Thumbnail Image'))->uniqueName();
        $form->file('file', __('File'))->uniqueName();
        $form->select('status', __('Status'))->options(Status::where('type', 'general')->pluck('name', 'id'))->rules(function ($form) {
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
