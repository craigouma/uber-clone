<?php

namespace App\Admin\Controllers;

use App\Models\SharedTripSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SharedTripSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SharedTripSetting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SharedTripSetting());

        $grid->column('id', __('Id'));
        $grid->column('pickup_radius', __('Pickup radius'));
        $grid->column('drop_radius', __('Drop radius'));
        $grid->column('max_bookings', __('Max bookings'));

        
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
        $form = new Form(new SharedTripSetting());

        $form->decimal('pickup_radius', __('Pickup radius'));
        $form->decimal('drop_radius', __('Drop radius'));
        $form->number('max_bookings', __('Max bookings'));

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
