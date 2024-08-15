<?php

namespace App\Admin\Controllers;

use App\UserType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User Types';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserType());

        $grid->column('id', __('Id'));
        $grid->column('type_name', __('User Type'));
        $grid->disableExport();
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        
        $grid->filter(function ($filter) {
            //Get All status
            $filter->like('type_name', 'User Type');
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
        $form = new Form(new USerType());

        $form->text('type_name', __('User Type'))->rules(function ($form) {
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
