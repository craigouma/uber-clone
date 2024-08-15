<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Status;
use App\Country;
use App\Currency;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DispatchCustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dispatch Customers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer);

        $grid->column('id', __('Id'));
        $grid->column('country_id', __('Country'))->display(function($countries){
            $country_name = Country::where('id',$countries)->value('country_name');
                return "$country_name";
        });
        $grid->column('first_name', __('First Name'));
        $grid->column('last_name', __('Last Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('email', __('Email'));
        $grid->column('password', __('Password'))->hide();
        $grid->column('profile_picture', __('Profile picture'))->hide();
        $grid->column('gender', __('Gender'))->display(function($status){
            if ($status == 0) {
                return "<span class='label label-success'>Not Updated</span>";
            } if ($status == 1) {
                return "<span class='label label-info'>Male</span>";
            } else {
                return "<span class='label label-warning'>Female</span>";
            }
        });
        $grid->column('country_code', __('Country code'));
        $grid->column('currency', __('Currency'))->display(function($currency){
            $currency = Currency::where('currency',$currency)->value('currency');
                return "$currency";
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
            $statuses = Status::where('type','general')->pluck('name','id'); 
            $countries = Country::pluck('country_name', 'id');

            $filter->disableIdFilter();
            $filter->like('first_name', 'First Name');
            $filter->like('last_name', 'Last Name');        
            $filter->equal('phone_number', 'Phone number');  
            $filter->equal('gender', 'Gender')->select(['1' => 'Male', '2'=> 'Female']);
            $filter->like('email', 'Email');
            $filter->equal('country_id', 'Country');
            $filter->equal('country_code', 'Country code');
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
        $form = new Form(new Customer);
        $statuses = Status::where('type','general')->pluck('name','id'); 
        $countries = Country::pluck('country_name', 'id');
        $currencies = Currency::pluck('currency', 'currency');

        $form->text('first_name', __('First name'))->rules('required|max:250');
        $form->text('last_name', __('Last name'))->rules('required|max:250');
        $form->text('phone_number', __('Phone number'))->rules('required|max:250');
        $form->email('email', __('Email'))->rules('required|max:250');
        $form->select('gender', __('gender'))->options(['1' => 'Male', '2'=> 'Female'])->default('1')->rules('required');
        $form->password('password', __('Password'))->rules('required|max:250');
        $form->image('profile_picture', __('Profile picture'))->move('customers/')->uniqueName()->rules('required');
        $form->select('country_id','Country')->options($countries)->rules('required');
        $form->text('country_code', __('Country code'))->rules('required|max:10');  
        $form->select('currency','Currency')->options($currencies)->rules('required');
        $form->select('status','Status')->options($statuses)->rules('required');

        $form->saving(function ($form) {
            if($form->password && $form->model()->password != $form->password)
            {
                $form->password = $this->getEncryptedPassword($form->password);
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

    public function getEncryptedPassword($input, $rounds = 12) {
        $salt = "";
        $saltchars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        for ($i = 0; $i < 22; $i++) {
            $salt .= $saltchars[array_rand($saltchars)];
        }
        return crypt($input, sprintf('$2y$%2d$', $rounds) . $salt);
    }
}
