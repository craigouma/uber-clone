<?php

namespace App\Admin\Controllers;

use App\Models\AppVersion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AppVersionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App Versions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AppVersion);

        $grid->column('platform', __('Platform'))->display(function($platform){
            if ($platform == 1) {
                return "<span class='label label-info'>Android</span>";
            } else {
                return "<span class='label label-info'>IOS</span>";
            }
        });
        $grid->column('version_number', __('Version Number'));
        $grid->column('version_code', __('Version Code'));
        $grid->column('date_of_upload', __('Date Of Upload'));
        $grid->column('date_of_approved', __('Date of Approved'));
       
        $grid->disableExport();
        
      
            $grid->disableActions();
    
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        
        
        
        $grid->filter(function ($filter) {
            $platforms = [1=>'Android', 2=>'IOS'];
            
            $filter->like('tax', 'Tax');
            $filter->equal('platform', 'Platform')->select($platforms);
        
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
        $form = new Form(new AppVersion);

        $form->select('platform', __('Platform'))->options(['1' => 'Android', '2'=> 'IOS'])->rules(function ($form) {
            return 'required';
        });
        $form->text('version_number', __('Version Number'));
        $form->text('version_code', __('Version Code'));
        $form->date('date_of_upload', __('Date Of Upload'));
        $form->date('date_of_approved', __('Date Of Approved'));
        
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
