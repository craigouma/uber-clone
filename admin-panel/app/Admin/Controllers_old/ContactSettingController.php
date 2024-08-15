<?php

namespace App\Admin\Controllers;

use App\ContactSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ContactSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Contact Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ContactSetting);

        $grid->column('id', __('Id'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('email', __('Email'));
        $grid->column('address', __('Address'));
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableExport();
        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
        }else{
            $grid->actions(function ($actions) {
               $actions->disableView();
               $actions->disableDelete();
            });
        }

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ContactSetting);

        $form->text('phone_number', __('Phone number'))->rules('required|max:250');
        $form->email('email', __('Email'))->rules('required|max:250');
        $form->textarea('address', __('Address'))->rules('required|max:250');
        $form->text('lat', __('Lat'))->rules('required|max:250');
        $form->text('lng', __('Lng'))->rules('required|max:250');

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
