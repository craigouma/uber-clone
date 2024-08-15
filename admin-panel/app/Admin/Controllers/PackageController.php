<?php

namespace App\Admin\Controllers;

use App\Models\Package;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PackageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Package';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Package());

        $grid->column('id', __('Id'));
        $grid->column('package_name', __('Package Name'));
        $grid->column('hours', __('Hours'));
        $grid->column('kilometers', __('Kilometers'));
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableEdit();
                $actions->disableDelete();
                
        });
        $grid->filter(function ($filter) {
            $filter->like('package_name', 'Package Name');
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
        $form = new Form(new Package());

        $form->text('package_name', __('Package Name'))->rules('required');
        $form->text('package_name_ar', __('Package Name Arabic'));
        $form->decimal('hours', __('Hours'))->rules('required');
        $form->decimal('kilometers', __('Kilometers'))->rules('required');

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
