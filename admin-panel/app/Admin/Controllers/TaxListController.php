<?php

namespace App\Admin\Controllers;

use App\TaxList;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TaxListController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tax Lists';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TaxList);

        $grid->column('id', __('Id'));
        $grid->column('tax_name', __('Tax name'));
        $grid->column('percent', __('Percent'));
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });

        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->disableRowSelector();
        $grid->disableExport();
        
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $statuses = Status::where('type','general')->pluck('name','id');

            $filter->like('tax_name', 'Tax Name');
            $filter->equal('percent', 'Percent');
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
        $form = new Form(new TaxList);
        $statuses = Status::where('type','general')->pluck('name','id');

        $form->text('tax_name', __('Tax name'))->rules('required|max:250');
        $form->decimal('percent', __('Percent'))->rules('required');
        $form->select('status','Status')->options($statuses)->rules('required');

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
