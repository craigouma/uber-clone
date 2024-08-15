<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Models\Subscription;
use App\Models\CustomerSubscriptionHistory;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerSubscriptionHistoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer Subscription Histories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerSubscriptionHistory);

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer'))->display(function($customer_id){
            $name = Customer::where('id',$customer_id)->value('first_name');
            return $name;
        });
        $grid->column('sub_id', __('Subscription'))->display(function($sub_id){
            $name = Subscription::where('id',$sub_id)->value('sub_name');
            return $name;
        });
        $grid->column('purchased_at', __('Purchase At'));
        $grid->column('expiry_at', __('Expired At'));
        $grid->disableActions();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        
        $grid->filter(function ($filter) {
            $customers = Customer::pluck('first_name', 'id');
            $subscriptions = Subscription::pluck('sub_name', 'id');
            
            $filter->disableIdFilter();
            $filter->equal('customer_id', 'Customer')->select($customers);
            $filter->equal('sub_id', 'Subscription')->select($subscriptions);
        
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
        $form = new Form(new CustomerSubscriptionHistory);
        $customers = Customer::pluck('first_name', 'id');
        $subscriptions = Subscription::pluck('sub_name', 'id');

        $form->select('customer_id', __('Customer'))->options($customers)->rules('required');
        $form->select('sub_id', __('Subscription'))->options($subscriptions)->rules('required');
        $form->date('purchased_at', __('Purchase'))->rules('required');
        $form->date('expiry_at', __('Expire'))->rules('required');
       
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
