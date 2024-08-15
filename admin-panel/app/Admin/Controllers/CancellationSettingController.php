<?php

namespace App\Admin\Controllers;

use App\CancellationSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CancellationSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Cancellation Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CancellationSetting);

        $grid->column('id', __('Id'));
        $grid->column('no_of_free_cancellation', __('No of free cancellation'));
        $grid->column('cancellation_charge', __('Cancellation charge'));
        $grid->disableCreateButton();
        
            $grid->disableRowSelector();
        
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableView();
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
        $form = new Form(new CancellationSetting);

        $form->number('no_of_free_cancellation', __('No of free cancellation'))->rules('required');
        $form->decimal('cancellation_charge', __('Cancellation charge'))->rules('required');
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
