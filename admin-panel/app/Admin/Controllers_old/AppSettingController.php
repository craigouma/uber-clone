<?php

namespace App\Admin\Controllers;

use App\AppSetting;
use App\Country;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AppSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AppSetting);

        $grid->column('id', __('Id'));
        $grid->column('app_name', __('App name'));
        $grid->column('app_version', __('Customer App version'));
        $grid->column('driver_app_version', __('Driver App version'));
        $grid->column('default_currency', __('Default currency'));
        $grid->column('default_currency_symbol', __('Default currency symbol'));
        $grid->column('subscription_status', __('Subscription Status'))->display(function($subscription_status){
            if($subscription_status == 1){
                return "Enable";
            }else{
                return "Disable";
            }
        });
        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableCreateButton();

        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
        }else{
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableView();
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
        $form = new Form(new AppSetting);
        $countries = Country::pluck('country_name', 'id');
        
        $form->text('app_name', __('App name'))->rules('required|max:250');
        $form->image('logo',__('App Logo'))->uniqueName()->rules('required');
        $form->text('app_version', __('Customer App version'))->rules('required|max:10');
        $form->text('driver_app_version', __('Driver App version'))->rules('required|max:10');
        $form->text('default_country', __('Default Country'))->rules('required');
        $form->text('phone_code', __('Phone Code'))->rules('required');
        $form->text('default_currency', __('Default currency'))->rules('required|max:100');
        $form->text('default_currency_symbol', __('Default currency symbol'))->rules('required|max:10');
        $form->text('currency_short_code', __('Currency Short Code'))->rules('required|max:10');
        $form->select('subscription_status', __('Subscription Status'))->options([ 1 => "Enable", 0 => "Disable"])->rules('required');
        $form->textarea('about_us', __('About Us'))->rules('required');
        $form->textarea('about_us_ar', __('About Us Ar'))->rules('required');
        $form->number('driver_trip_time', __('Driver Trip Request Time'))->rules('required');
        $form->text('referral_amount', __('Referral Amount'))->rules('required');
        $form->text('driver_referral_amount', __('Driver Referral Amount'))->rules('required');
        $form->text('capital_lat', __('Capital Lat'))->rules('required');
        $form->text('capital_lng', __('Capital Lng'))->rules('required');

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
