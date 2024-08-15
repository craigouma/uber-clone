<?php

namespace App\Admin\Controllers;

use App\Status;
use App\LuckyOffer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LuckyOfferController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Lucky Offer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LuckyOffer());

        $grid->column('id', __('Id'));
        $grid->column('offer_name', __('Offer name'));
        $grid->column('image', __('Image'))->image();
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
            
            $filter->disableIdFilter();
            $filter->equal('offer_name', 'Offer Name');     
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
        $form = new Form(new LuckyOffer());

        $statuses = Status::where('type','general')->pluck('name','id');

        $form->text('offer_name', __('Offer Name'))->rules('required|max:250');
        $form->textarea('offer_description', __('Description'))->rules('required');
        $form->text('offer_name_ar', __('Offer Name Arabic'));
        $form->textarea('offer_description_ar', __('Description Arabic'));
        $form->image('image', __('Image'));
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
