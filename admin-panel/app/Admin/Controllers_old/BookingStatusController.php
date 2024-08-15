<?php

namespace App\Admin\Controllers;

use App\BookingStatus;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BookingStatusController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Booking Status';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BookingStatus);

        $grid->column('id', __('Id'));
        $grid->column('status_name', __('Status Name'));
        $grid->column('customer_status_name', __('Customer Status Name'));
     
        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
        }
        
        $grid->filter(function ($filter) {
            //Get All status
            $filter->like('status_name', 'Status Name');
            $filter->like('customer_status_name', 'Customer Status Name');
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
        $form = new Form(new BookingStatus);

        $form->text('status_name', __('Status Name'));
        $form->text('customer_status_name', __('Customer Status Name'));
        $form->text('status_name_ar', __('Status Name Arabic'));
        $form->text('customer_status_name_ar', __('Customer Status Name Arabic'));
        
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
