<?php

namespace App\Admin\Controllers;

use App\Faq;
use App\UserType;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FaqController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Faqs';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new faq);

        $grid->column('id', __('Id'));
        $grid->column('user_type_id', __('User Type'))->display(function($types){
            $types = UserType::where('id',$types)->value('type_name');
                return "$types";
        });
        $grid->column('question', __('Question'));
        $grid->column('answer', __('Answer'));
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
            $filter->like('question', 'Question');
            $filter->like('answer', 'Answer');
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
        $form = new Form(new faq);
        $statuses = Status::where('type','general')->pluck('name','id');
        $types = UserType::pluck('type_name', 'id');
        
        $form->select('user_type_id','User Type')->options($types)->rules('required');
        $form->text('question', __('Question'))->rules('required|max:250');
        $form->textarea('answer', __('Answer'))->rules('required');
        $form->text('question_ar', __('Question Arabic'));
        $form->textarea('answer_ar', __('Answer Arabic'));
        $form->select('status','Status')->options($statuses)->rules('required');

        $form->footer(function ($footer) {
        $footer->disableViewCheck();
        $footer->disableEditingCheck();
        $footer->disableCreatingCheck();

        });

        $form->tools(function (Form\Tools $tools) {
        $tools->disableDelete(); 
        $tools->disableView();
        

        });

        return $form;
    }
}
