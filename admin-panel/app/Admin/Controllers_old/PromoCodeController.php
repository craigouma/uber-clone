<?php

namespace App\Admin\Controllers;

use App\PromoCode;
use App\Status;
use App\Customer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PromoCodeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Promo Codes';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PromoCode);

        $grid->column('id', __('Id'));
      	$grid->column('customer_id', __('Customer'))->display(function($customer_id){
            if($customer_id == 0){
                return "NILL";
            }else{
                $customer_id = Customer::where('id',$customer_id)->value('first_name');
            return $customer_id;
            }
        });
        $grid->column('promo_name', __('Promo name'));
        $grid->column('promo_code', __('Promo code'));
        $grid->column('description', __('Description'))->hide();
        $grid->column('promo_type', __('Promo type'))->display(function(){
            $value = Status::where('id',$this->promo_type)->value('name');
            return $value;
        });
        $grid->column('discount', __('Discount'));
        $grid->column('redemptions', __('Redemptions'));
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
            $statuses = Status::where('type','general')->pluck('name','id');
            $promo_types = Status::where('type','promo_type')->pluck('name','id');
            
            $filter->disableIdFilter();
            $filter->like('promo_type', 'Promo type')->select($promo_types);
            $filter->like('promo_name', 'Promo name');
            $filter->like('promo_code', 'Promo code');
            $filter->like('discount', 'Discount');
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
        $form = new Form(new PromoCode);
        $statuses = Status::where('type','general')->pluck('name','id');
        $promo_types = Status::where('type','promo_type')->pluck('name','id');
        $customers = Customer::pluck('first_name', 'id');

        $form->select('customer_id', __('Customer'))->options($customers)->default(0);
        $form->text('promo_name', __('Promo name'))->rules('required|max:250');
        $form->text('promo_code', __('Promo code'))->rules('required|max:250');
        $form->textarea('description', __('Description'))->rules('required');
        $form->text('promo_name_ar', __('Promo Name Arabic'));
        $form->text('promo_code_ar', __('Promo Code Arabic'));
        $form->textarea('description_ar', __('Description Arabic'));
        $form->select('promo_type', __('Promo type'))->options($promo_types)->rules('required');
        $form->decimal('discount', __('Discount'))->rules('required');
        $form->decimal('min_fare', __('Minimum Fare'))->rules('required');
        $form->decimal('max_discount_value', __('Maximum Discount Value'))->rules('required');
        $form->text('redemptions', __('Redemptions'))->rules('required');
        $form->select('status', __('Status'))->options($statuses)->rules('required');
        
        // callback before save
        $form->saving(function (Form $form) {
            if(!$form->customer_id){
                $form->customer_id = 0;
            }
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
