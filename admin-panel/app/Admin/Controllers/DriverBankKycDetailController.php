<?php

namespace App\Admin\Controllers;

use App\DriverBankKycDetail;
use App\Status;
use App\Driver;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverBankKycDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Bank & Kyc Verfication Details';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverBankKycDetail());

        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver name'))->display(function($driver){
            $driver_name = Driver::where('id',$driver)->value('first_name');
                return "$driver_name";
        });
        $grid->column('bank_name', __('Bank name'));
        $grid->column('bank_account_number', __('Bank account number'));
        $grid->column('ifsc_code', __('IfSC code'));
        $grid->column('aadhar_number', __('Aadhar number'));
        $grid->column('pan_number', __('Pan number'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });

     
          
            $grid->disableRowSelector();
            $grid->disableCreateButton();
      
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableView();
            });
       
        
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(DriverBankKycDetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('driver_id', __('Driver Name'))->as(function($driver){
            $driver_name = Driver::where('id',$driver)->value('first_name');
                return "$driver_name";
        });
        $show->field('bank_name', __('Bank Name'));
        $show->field('bank_account_number', __('Bank Account Number'));
        $show->field('ifsc_code', __('Ifsc Code'));
        $show->field('aadhar_number', __('Aadhar Number'));
        $show->field('pan_number', __('Pan Number'));
        $show->field('status', __('Status'))->as(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DriverBankKycDetail());

        $form->number('driver_id', __('Driver id'));
        $form->text('bank_name', __('Bank name'));
        $form->text('bank_account_number', __('Bank account number'));
        $form->text('ifsc_code', __('Ifsc code'));
        $form->text('aadhar_number', __('Aadhar number'));
        $form->text('pan_number', __('Pan number'));
        $form->number('status', __('Status'))->default(1);
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
