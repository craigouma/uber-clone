<?php

namespace App\Admin\Controllers;
use App\Status;
use App\InstantOffer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InstantOfferController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Instant Offer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InstantOffer());

        $grid->column('id', __('Id'));
        $grid->column('offer_name', __('Offer Name'));
        $grid->column('discount_type', __('Discount Type'))->display(function(){
            $value = Status::where('id',$this->discount_type)->value('name');
            return $value;
        });
        $grid->column('discount', __('discount'));
        
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
            $discount_types = Status::where('type','promo_type')->pluck('name','id');
            $filter->disableIdFilter();
            $filter->like('discount_type', 'Discount type')->select($discount_types);
        
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
        $form = new Form(new InstantOffer());
        $discount_types = Status::where('type','promo_type')->pluck('name','id');
        
        $form->text('offer_name', __('Offer Name'))->rules(function ($form) {
            return 'required';
        });
        $form->textarea('offer_description', __('Description'))->rules(function ($form) {
            return 'required';
        });
        $form->text('offer_name_ar', __('Offer Name Arabic'));
        $form->textarea('offer_description_ar', __('Description Arabic'));
        $form->select('discount_type', __('Discount Type'))->options($discount_types)->rules('required');
       
        $form->text('discount', __('Discount'))->rules(function ($form) {
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
