<?php

namespace App\Admin\Controllers;

use App\Status;
use App\Models\PaymentType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment Type';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentType());

        $grid->column('id', __('Id'));
        $grid->column('payment_type', __('Payment type'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            
            $filter->like('payment_type', 'Payment Type');
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
        $form = new Form(new PaymentType());

        $statuses = Status::where('type','general')->pluck('name','id');

        $form->text('payment_type', __('Payment type'))->rules('required');
        $form->text('payment_type_ar', __('Payment type Arabic'));
        $form->select('status', __('Status'))->options($statuses)->rules('required');

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
