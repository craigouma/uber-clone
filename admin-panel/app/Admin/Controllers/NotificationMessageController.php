<?php

namespace App\Admin\Controllers;

use App\NotificationMessage;
use App\Status;
use App\UserType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NotificationMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Notification Messages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NotificationMessage);

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'))->display(function($types){
            $types = UserType::where('id',$types)->value('type_name');
                return "$types";
        });
        $grid->column('title', __('Title'));
        $grid->column('message', __('Message'));
        $grid->column('image', __('Image'))->hide();
        $grid->column('status', __('Status'))->display(function($status){
            $status_name = Status::where('id',$status)->value('name');
            if ($status == 1) {
                return "<span class='label label-success'>$status_name</span>";
            } else {
                return "<span class='label label-danger'>$status_name</span>";
            }
        });
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
        });
        $grid->filter(function ($filter) {
            $statuses = Status::pluck('name', 'id');
            $filter->disableIdFilter();
            $filter->like('type', 'Type');
            $filter->like('message', 'Message');
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
        $form = new Form(new NotificationMessage);
        $statuses = Status::where('type','general')->pluck('name','id');
        $types = UserType::pluck('type_name', 'id');
        
        $form->select('type', __('Type'))->options($types)->rules('required');
        $form->text('title', __('Title'))->rules('required|max:250');
        $form->textarea('message', __('Message'))->rules('required');
        $form->text('title_ar', __('Title Arabic'));
        $form->textarea('message_ar', __('Message Arabic'));
        $form->image('image', __('Image'))->uniqueName()->rules('required');
        $form->select('status', __('Status'))->options($statuses)->rules('required');

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
