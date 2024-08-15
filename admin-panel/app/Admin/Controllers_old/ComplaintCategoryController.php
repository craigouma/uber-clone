<?php

namespace App\Admin\Controllers;

use App\ComplaintCategory;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ComplaintCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Complaint Categories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ComplaintCategory);

        $grid->column('id', __('Id'));
        $grid->column('complaint_category_name', __('Complaint category'));
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
            $filter->like('complaint_category_name', 'complaint category');
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
        $form = new Form(new ComplaintCategory);
        $statuses = Status::where('type','general')->pluck('name','id');

        $form->text('complaint_category_name', __('Complaint category'))->rules('required|max:250');
        $form->text('complaint_category_name_ar', __('Complaint category Arabic'));
        $form->select('status', __('Status'))->options($statuses)->default(1)->rules('required');

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
