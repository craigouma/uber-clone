<?php

namespace App\Admin\Controllers;

use App\ComplaintSubCategory;
use App\Status;
use App\ComplaintCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ComplaintSubCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Complaint Sub Categories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ComplaintSubCategory);

        $grid->column('id', __('Id'));
        $grid->column('complaint_category', __('Complaint category'))->display(function(){
            return ComplaintCategory::where('id',$this->complaint_category)->value('complaint_category_name');
        });
        $grid->column('complaint_sub_category_name', __('Complaint sub category'));
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
               $actions->disableView();
            });
        
        
        $grid->filter(function ($filter) {
            $statuses = Status::where('type','general')->pluck('name','id');
            $complaint_categories = ComplaintCategory::where('status',1)->pluck('complaint_category_name','id');

            $filter->disableIdFilter();
            $filter->like('complaint_category', 'Complaint category')->select( $complaint_categories);
            $filter->like('complaint_sub_category_name', 'Complaint sub category');
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
        $form = new Form(new ComplaintSubCategory);
        $statuses = Status::where('type','general')->pluck('name','id');
        $complaint_categories = ComplaintCategory::where('status',1)->pluck('complaint_category_name','id');

        $form->select('complaint_category', __('Complaint category'))->options($complaint_categories)->rules(function ($form) {
            return 'required';
        });
        $form->text('complaint_sub_category_name', __('Complaint sub category'))->rules('required|max:250');
        $form->textarea('short_description', __('Short description'))->rules('required|max:250');
        $form->text('complaint_sub_category_name_ar', __('Complaint sub category Arabic'));
        $form->textarea('short_description_ar', __('Short description Arabic'));
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
