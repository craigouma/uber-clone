<?php

namespace App\Admin\Controllers;

use App\CustomerWalletHistory;
use App\Status;
use App\Customer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerWalletHistoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer Wallet Histories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerWalletHistory);

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer'))->display(function($customer_id){
            return Customer::where('id',$customer_id)->value('first_name');
        });
        $grid->column('type', __('Type'))->display(function($type){
            if ($type == 1) {
                return "<span class='label label-warning'>Credit</span>";
            }if ($type == 2) {
                return "<span class='label label-success'>Debit</span>";
            } 
        });
        $grid->column('message', __('Message'));
        $grid->column('amount', __('Amount'));
        $grid->column('transaction_type', __('Transaction type'))->display(function($amount_type){
            if ($amount_type == 1) {
                return "Customer added amount";
            }if ($amount_type == 2) {
                return "Refund amount";
            }if ($amount_type == 3) {
                return "Amount debited for booking";
            } 
        });
        
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            
        });

        $grid->disableRowSelector();
        $grid->disableCreateButton();
        
        $grid->filter(function ($filter) {
            $customers = Customer::pluck('first_name', 'id');
            $filter->equal('customer_id', 'Customer')->select($customers);
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
        $form = new Form(new CustomerWalletHistory);
        $customers = Customer::pluck('first_name', 'id');

        $form->select('customer_id', __('Customer'))->options($customers)->rules(function ($form) {
            return 'required';
        });
        $form->select('type', __('Type'))->options(['1' => 'Credit', '2'=> 'Debit'])->rules(function ($form) {
            return 'required';
        });
        $form->text('message', __('Message'))->rules(function ($form) {
            return 'required';
        });
        $form->decimal('amount', __('Amount'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->select('transaction_type', __('Transaction type'))->options(['1' => 'Customer added amount', '2'=> 'Refund amount'])->rules(function ($form) {
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
