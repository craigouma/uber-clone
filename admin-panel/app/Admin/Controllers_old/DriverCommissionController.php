<?php

namespace App\Admin\Controllers;
use App\Booking;
use App\PaymentList;
use App\Status;
use App\Customer;
use App\DriverCommission;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverCommissionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Commission';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverCommission());

        $grid->column('id', __('Id'));
        $grid->column('booking_id', __('Booking id'))->display(function($booking){
            $booking_detail = Booking::where('id',$booking)->value('booking_detail');
              return "$booking_detail";
        });
        $grid->column('customer_id', __('Customer id'))->display(function($customer){
            $first_name = Customer::where('id',$customer)->value('first_name');
              return "$first_name";
        });
        $grid->column('picked_up', __('Picked Up'));
        $grid->column('dropped', __('Dropped'));
        $grid->column('commission', __('Commission'));
        $grid->column('mode_of_payment', __('Mode Of Payment'))->display(function($Payment_list){
            $payment_mode = PaymentList::where('id',$Payment_list)->value('payment_mode');
              return "$payment_mode";
        });
        $grid->column('date', __('Date'));
       $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('status_name');
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
            });
        }
        
        $grid->filter(function ($filter) {
            //Get All status
            $statuses = Status::pluck('status_name', 'id');
            $bookings = Booking::pluck('booking_detail', 'id');
            $customers = Customer::pluck('first_name', 'id');
            $Payment_lists = PaymentList::pluck('payment_mode', 'id');
            $filter->equal('booking_id', 'Booking_id')->select($bookings);
            $filter->equal('customer_id', 'Customer_id')->select($customers);
            $filter->like('picked_up', 'Picked_Up');
            $filter->like('dropped', 'Dropped');
            $filter->like('commission', 'Commission');
            $filter->equal('mode_of_payment', 'mode_Of_Payment')->select($Payment_lists);
            $filter->like('date', 'Date');
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
        $form = new Form(new DriverCommission());
        $statuses = Status::pluck('status_name', 'id');
        $bookings = Booking::pluck('booking_detail', 'id');
        $customers = Customer::pluck('first_name', 'id');
        $Payment_lists = PaymentList::pluck('payment_mode', 'id');
        $form->select('booking_id', __('Booking id'))->options($bookings)->rules(function ($form) {
            return 'required';
        });
        $form->select('customer_id', __('Customer id'))->options($customers)->rules(function ($form) {
            return 'required';
        });
        $form->text('picked_up', __('Picked Up'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->text('dropped', __('Dropped'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->text('commission', __('Commission'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->select('mode_of_payment', __('Mode Of Payment'))->options($Payment_lists)->rules(function ($form) {
            return 'required';
        });
        $form->text('date', __('Date'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->select('status', __('Status'))->options($statuses)->default(1)->rules(function ($form) {
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
