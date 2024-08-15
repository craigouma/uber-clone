<?php

namespace App\Admin\Controllers;

use App\Models\SurgeFareSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SurgeFareSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Surge Fare Setting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SurgeFareSetting());

        $grid->column('id', __('Id'));
        $grid->column('requests', __('Requests'));
        $grid->column('surge', __('Surge'))->display(function($surge){
            return $surge.'x';
        });
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SurgeFareSetting());

        $form->decimal('surge', __('Surge'))->rules('required');
        $form->number('requests', __('Requests'))->rules('required');
        
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
