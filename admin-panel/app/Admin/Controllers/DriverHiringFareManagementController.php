<?php

namespace App\Admin\Controllers;

use App\Models\DriverHiringFareManagement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverHiringFareManagementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Hiring Fare Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverHiringFareManagement());

        $grid->column('id', __('Id'));
        $grid->column('base_fare', __('Base Fare'));
        $grid->column('base_hours', __('Base Hours'));
        $grid->column('extra_hour_charge', __('Extra Hour Charge'));
            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
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
        $form = new Form(new DriverHiringFareManagement());

        $form->decimal('base_fare', __('Base Fare'));
        $form->text('base_hours', __('Base Hours'));
        $form->decimal('extra_hour_charge', __('Extra Hour Charge'));

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
