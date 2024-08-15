<?php

namespace App\Admin\Controllers;
use App\Models\Subscription;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SubscriptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Subscriptions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Subscription());

        $grid->column('id', __('Id'));
        $grid->column('sub_name', __('Subscription Name'));
        $grid->column('sub_image', __('Subscription Image'))->image();
        $grid->column('sub_description', __('Short Name'));
        $grid->column('amount', __('Amount'));
        $grid->column('free_bookings', __('Short Name'));
        $grid->column('validity', __('Validity'));
        $grid->column('validity_label', __('Validity Label'));
        
        $grid->disableCreateButton();
        
        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
            $grid->disableRowSelector();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
        }

        $grid->filter(function ($filter) {
            $filter->like('sub_name', 'Subscription Name');
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
        $form = new Form(new Subscription());

        $form->text('sub_name', __('Subscription Name'))->rules(function ($form) {
            return 'required';
        });
        $form->image('sub_image', __('Subscription Image'))->move('subscriptions/')->uniqueName()->rules('required');
        $form->textarea('sub_description', __('Subscription Description'))->rules(function ($form) {
            return 'required';
        });
        $form->number('amount', __('Amount'))->rules(function ($form) {
            return 'required';
        });
        $form->decimal('free_bookings', __('Free Bookings'));
        $form->decimal('validity', __('Validity'));
        $form->text('validity_label', __('Validity Label'))->rules(function ($form) {
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