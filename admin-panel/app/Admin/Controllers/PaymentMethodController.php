<?php

namespace App\Admin\Controllers;

use App\PaymentMethod;
use App\Status;
use App\Models\PaymentType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentMethodController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment Methods';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentMethod);

        $grid->column('id', __('Id'));
        $grid->column('payment', __('Payment'));
        $grid->column('payment_type', __('Payment Type'))->display(function($payment){
            $payment_type = PaymentType::where('id',$payment)->value('payment_type');
            
                return "$payment_type";
            
        });
        $grid->column('icon', __('Icon'))->image();
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            
            $filter->disableIdFilter();
            $filter->like('payment', 'Payment');
            $filter->equal('status', 'Status')->select($statuses);        
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
        $form = new Form(new PaymentMethod);
        $statuses = Status::where('type','general')->pluck('name','id');
        $payment_types = PaymentType::pluck('payment_type', 'id');
        
        $form->text('payment', __('Payment'))->rules('required|max:250');
        $form->text('payment_ar', __('Payment Arabic'));
        $form->select('payment_type', __('Payment Type'))->options($payment_types);
        $form->image('icon', __('Icon'))->uniqueName();
        $form->select('status','Status')->options($statuses)->rules('required');

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
