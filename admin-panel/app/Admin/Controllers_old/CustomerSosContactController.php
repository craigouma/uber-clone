<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Status;
use App\Models\CustomerSosContact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerSosContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer Sos Contacts';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerSosContact());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer'))->display(function($customer_id){
            return Customer::where('id',$customer_id)->value('first_name');
        });
        $grid->column('name', __('Name'));
        $grid->column('phone_number', __('Phone Number'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
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
        
            $statuses = Status::where('type','general')->pluck('name','id');
            $customers = Customer::pluck('first_name', 'id');
         
            $filter->like('phone_number', 'Phone Number');
            $filter->equal('customer', 'Customer')->select($customers);
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
        $form = new Form(new CustomerSosContact());

         $statuses = Status::where('type','general')->pluck('name','id');
         $customers = Customer::pluck('first_name', 'id');

        $form->select('customer_id', __('Customer Id'))->options($customers)->rules('required');
        $form->text('name', __('Name'));
        $form->text('phone_number', __('Phone Number'));
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
