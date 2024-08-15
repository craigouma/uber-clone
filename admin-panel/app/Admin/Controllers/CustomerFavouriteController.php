<?php

namespace App\Admin\Controllers;

use App\Models\CustomerFavourite;
use App\Customer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerFavouriteController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer Favourites';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerFavourite());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer'))->display(function($customer){
            $customer_name = Customer::where('id',$customer)->value('first_name');
            return $customer_name;
        });
        $grid->column('address', __('Address'));
        $grid->column('lat', __('Lat'));
        $grid->column('lng', __('Lng'));
        
       
          
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
        

        $grid->filter(function ($filter) {
            $customer = Customer::pluck('first_name', 'id');
        
            $filter->equal('customer_id', 'Customer')->select($customer);
            $filter->equal('type', 'Type')->select([1 => 'Pickup Location', 2 => 'Drop Location']);
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
        $form = new Form(new CustomerFavourite());

        $form->number('customer_id', __('Customer id'));
        $form->text('address', __('Address'));
        $form->text('lat', __('Lat'));
        $form->text('lng', __('Lng'));
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
