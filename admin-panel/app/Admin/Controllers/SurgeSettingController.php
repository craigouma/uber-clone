<?php

namespace App\Admin\Controllers;

use App\Models\SurgeSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SurgeSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Surge Setting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SurgeSetting());

        $grid->column('id', __('Id'));
        $grid->column('searching_time', __('Searching Time (in Seconds)'));
        $grid->column('minimum_trips', __('Minimum Trips'));
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->disableRowSelector();
        
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SurgeSetting());

        $form->number('searching_time', __('Searching Time (in Seconds)'))->rules('required');
        $form->number('minimum_trips', __('Minimum Trips'))->rules('required');
        
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
