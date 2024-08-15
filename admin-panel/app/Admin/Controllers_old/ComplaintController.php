<?php

namespace App\Admin\Controllers;

use App\Complaint;
use App\Status;
use App\Customer;
use App\Driver;
use App\ComplaintCategory;
use App\ComplaintSubCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ComplaintController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Complaints';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Complaint);

        $grid->column('id', __('Id'));
        $grid->column('trip_id', __('Trip id'));
        $grid->column('customer_id', __('Customer id'))->display(function(){
            $value = Customer::where('id',$this->customer_id)->value('first_name');
            return $value;
        });
        $grid->column('driver_id', __('Driver id'))->display(function(){
            $value = Driver::where('id',$this->driver_id)->value('first_name');
            return $value;
        });
        $grid->column('complaint_category', __('Complaint category'))->display(function(){
            $value = ComplaintCategory::where('id',$this->complaint_category)->value('complaint_category_name');
            return $value;
        });
        $grid->column('complaint_sub_category', __('Complaint sub category'))->display(function(){
            $value = ComplaintSubCategory::where('id',$this->complaint_sub_category)->value('complaint_sub_category_name');
            return $value;
        });
        $grid->column('subject', __('Subject'));
        $grid->column('status', __('Status'))->display(function($status){
            return Status::where('id',$status)->value('name');
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
            $statuses = Status::where('type','customer_complaints')->pluck('name', 'id');
            $customers = Customer::where('status',1)->pluck('first_name','id');
            $drivers = Driver::where('status',1)->pluck('first_name','id');

            $filter->like('trip_id', 'Trip id');
            $filter->equal('customer_id', 'Customer id')->select($customers);        
            $filter->equal('driver_id', 'Driver id')->select($drivers);        
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
        $form = new Form(new Complaint);
        $statuses = Status::where('type','customer_complaints')->pluck('name','id');
        $customers = Customer::where('status',1)->pluck('first_name','id');
        $drivers = Driver::where('status',1)->pluck('first_name','id');
        $complaint_categories = complaintCategory::pluck('complaint_category_name','id');
        $complaint_sub_categories = complaintSubCategory::pluck('complaint_sub_category_name','id');


        $form->text('trip_id', __('Trip id'))->rules('required');
        $form->select('customer_id', __('Customer id'))->options($customers)->rules('required');
        $form->select('driver_id', __('Driver id'))->options($drivers)->rules('required');
        $form->select('complaint_category', __('Complaint category'))->load('complaint_sub_category', '/admin/get_complaint_sub_category', 'id', 'complaint_sub_category_name')->options($complaint_categories)->rules(function ($form) {
            return 'required';
        });
        $form->select('complaint_sub_category', __('Complaint sub scategory'))->options($complaint_sub_categories)->rules('required');
        $form->text('subject', __('Subject'))->rules('required');
        $form->textarea('description', __('Description'))->rules('required');
        $form->select('status','Status')->options($statuses)->rules('required');

        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete(); 
            $tools->disableView();
        });

        return $form;
    }
}
