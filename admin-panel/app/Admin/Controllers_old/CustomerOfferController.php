<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Status;
use App\OfferType;
use App\Models\LuckyOffer;
use App\InstantOffer;
use App\Models\CustomerOffer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerOfferController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer Rewards';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerOffer());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer id'))->display(function($customer_id){
            return Customer::where('id',$customer_id)->value('first_name');
        });
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'))->hide();
        //$grid->column('expiry_date', __('Expiry date'));
        $grid->column('view_status', __('View Status'))->display(function($status){
            if ($status == 0) {
                return "<span class='label label-danger'>Not Viewed</span>";
            } else if ($status == 1) {
                return "<span class='label label-info'>Viewed by Customer</span>";
            }
        });
        
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
            });
        }
        
        $grid->filter(function ($filter) {
            $statuses = Status::pluck('name','id'); 
            $customers = Customer::pluck('first_name', 'id');

            $filter->equal('customer_id', 'Customer id')->select($customers);
            $filter->like('title', 'Title');        
            $filter->like('description', 'Description');        
            //$filter->like('expiry_date', 'Expiry date');
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
        $form = new Form(new CustomerOffer());

         $statuses = Status::pluck('name','id'); 
         $customers = Customer::pluck('first_name', 'id');
         $types = OfferType::pluck('type', 'id');
         $get_offer = OfferType::pluck('type', 'id');

        $form->select('customer_id', __('Customer id'))->options($customers)->rules(function ($form) {
                    return 'required';
                });
        $form->text('title', __('Title'))->rules(function ($form) {
                    return 'required';
                });
        $form->textarea('description', __('Description'))->rules(function ($form) {
                    return 'required';
                });
        $form->text('title_ar', __('Title Arabic'));
        $form->textarea('description_ar', __('Description Arabic'));
        
        $form->select('type', __('Type'))->load('ref_id', '/admin/get_offers', 'id', 'offer_name')->options($types)->rules(function ($form) {
            return 'required';
        });
        $form->select('ref_id', __('Ref id'))->options(function(){
            if($this->type == 1){
                return InstantOffer::pluck('offer_name','id');
            }else if($this->type == 2){
                return LuckyOffer::pluck('offer_name','id');
            }
        });
        
        $form->select('view_status', __('View Status'))->options(['0' => 'Not Viewed', '1'=> 'Viewed by Customer'])->default(0)->rules(function ($form) {
            return 'required';
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
