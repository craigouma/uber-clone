<?php

namespace App\Admin\Controllers;

use App\Trip;
use App\Models\PaymentHistory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentHistoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment History';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentHistory());

        $grid->column('id', __('Id'));
        $grid->column('trip_id', __('Trip'))->display(function($trip_id){
            return Trip::where('id',$trip_id)->value('trip_id');
        });
        $grid->column('mode', __('Mode'));
        $grid->column('amount', __('Amount'));
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            //Get All status
            $trips = Trip::pluck('trip_id', 'id');

            $filter->equal('trip_id', 'Trip')->select($trips);
            $filter->like('mode', 'Mode');
            
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
        $form = new Form(new PaymentHistory());

        $trips = Trip::pluck('trip_id', 'id');

        $form->select('trip_id', __('Trip'))->options($trips)->rules(function ($form) {
            return 'required';
        });
        $form->text('mode', __('Mode'))->rules(function ($form) {
            return 'required';
        });
        $form->decimal('amount', __('Amount'))->rules(function ($form) {
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
