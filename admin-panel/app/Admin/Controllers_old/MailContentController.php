<?php

namespace App\Admin\Controllers;

use App\MailContent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MailContentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Mail Contents';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MailContent);

        $grid->column('id', __('Id'));
        $grid->column('code', __('Code'));
        $grid->column('title', __('Title'));
        $grid->column('content', __('Content'));
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
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
            $filter->disableIdFilter();
            $filter->like('code', 'Code');
            $filter->like('title', 'Title');        
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
        $form = new Form(new MailContent);

        $form->text('code', __('Code'))->rules('required|max:250');
        $form->text('title', __('Title'))->rules('required|max:250');
        $form->ckeditor('content', __('Content'))->rules('required');
        $form->text('title_ar', __('Title Arabic'));
        $form->ckeditor('content_ar', __('Content Arabic'));
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
