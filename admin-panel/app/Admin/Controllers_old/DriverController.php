<?php

namespace App\Admin\Controllers;

use App\Driver;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

class DriverController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Drivers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Driver);

        $grid->column('id', __('Id'));
        $grid->column('first_name', __('First Name'));
        $grid->column('last_name', __('Last Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('email', __('Email'));
        $grid->column('password', __('Password'))->hide();
        $grid->column('profile_picture', __('Profile picture'))->hide();
        $grid->column('date_of_birth', __('Date of birth'))->hide();
        $grid->column('licence_number', __('Licence number'));
        $grid->column('id_proof', __('Id proof'))->hide();
        $grid->column('address', __('Address'))->hide();
        $grid->column('id_proof_status', __('Id Proof Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 14) {
                return "<span class='label label-warning'>$status_name</span>";
            } if ($status == 15) {
                return "<span class='label label-info'>$status_name</span>";
            }if ($status == 16) {
                return "<span class='label label-success'>$status_name</span>";
            }if ($status == 17) {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->column('View Documents')->display(function () {
            return "<a href='/admin/view_documents/".$this->id."'><span class='label label-info'>View Documents</span></a>";
        });

        if(env('MODE') == 'DEMO'){
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        }else{
            $grid->disableCreateButton();
            $grid->actions(function ($actions) {
                $actions->disableView();
                
            });
        }
        
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');

            //$filter->disableIdFilter();
            $filter->like('first_name', 'First Name');
            $filter->like('last_name', 'Last Name');        
            $filter->equal('phone_number', 'Phone number');        
            $filter->like('email', 'Email');
            $filter->equal('licence_number', 'Licence number');
            $filter->equal('online_status', 'Online Status')->select([1 => 'Online', 0 => 'Offline']);
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
        $form = new Form(new Driver);
        $statuses = Status::where('type','general')->pluck('name','id');
        $proof_statuses = Status::where('type','verification_status')->pluck('name','id');
        $form->text('first_name', __('First name'))->rules('required|max:250');
        $form->text('last_name', __('Last name'))->rules('required|max:250');
        $form->text('phone_number', __('Phone number'))->rules(function ($form) {
            return 'numeric|digits_between:6,20|required';
        });
         $form->text('phone_with_code', __('Phone number with code'))->rules(function ($form) {
            return 'required';
        });
        $form->email('email', __('Email'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->password('password', __('Password'))->rules('required|max:250');
        $form->image('profile_picture', __('Profile picture'))->move('drivers')->uniqueName()->rules('required');
        $form->date('date_of_birth', __('Date of birth'))->default(date('Y-m-d'))->rules('required');
        $form->text('licence_number', __('Licence number'))->rules('required|max:250');
        $form->image('id_proof', __('Id proof'))->move('drivers/vehicle_documents')->uniqueName()->rules('required|max:250');
        $form->select('id_proof_status','Id Proof Status')->default(15)->options($proof_statuses)->rules('required');
        $form->select('status','Status')->options($statuses)->default(1)->rules('required');
        
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
        $form->saved(function (Form $form) {
           // $this->update_status($form->model()->id,$form->model()->status,$form->model()->first_name,$form->model()->gender);
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
