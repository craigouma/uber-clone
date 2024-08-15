<?php

namespace App\Admin\Controllers;

use App\DriverWithdrawal;
use App\Driver;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Admin;

class DriverWithdrawalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Withdrawal';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverWithdrawal);
        
        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver'))->display(function($driver){
            $driver = Driver::where('id',$driver)->value('first_name');
                return $driver;
        });
        $grid->column('amount', __('Amount'));
        $grid->column('reference_proof', __('Reference proof'))->image();
        $grid->column('reference_no', __('Reference no'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->where('type','withdrawal')->value('name');
            if ($status == 11) {
                return "<span class='label label-warning'>$status_name</span>";
            } if ($status == 12) {
                return "<span class='label label-success'>$status_name</span>";
            }if ($status == 13) {
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
        
        return $grid;
    }
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DriverWithdrawal);
        $drivers = Driver::pluck('first_name', 'id');
        

        $form->select('driver_id', __('Driver id'))->options($drivers)->rules(function ($form) {
                return 'required';
            });
        $form->decimal('amount', __('Amount'))->readonly();
        $form->image('reference_proof', __('Reference proof'));
        $form->text('reference_no', __('Reference no'));
        $form->select('status', __('Status'))->options(Status::where('type','withdrawal')->pluck('name','id'))->rules(function ($form) {
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
        $form->saved(function (Form $form) {
            if($form->model()->status == 13){
                $this->update_payment($form->model()->driver_id,$form->model()->amount);
            }
        });

        return $form;
    }
    
    public function update_payment($id,$amount){
        Driver::where('id',$id)->update([ 'wallet' => $amount]);
    }
}
