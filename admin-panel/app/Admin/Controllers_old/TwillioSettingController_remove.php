<?php

namespace App\Admin\Controllers;

use App\TwillioSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TwillioSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Twillio Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TwillioSetting);

        $grid->column('id', __('Id'));
        $grid->column('twillio_sid', __('Twillio sid'));
        $grid->column('twillio_auth_foken', __('Twillio auth foken'));
        $grid->column('twillio_number', __('Twillio number'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at')); 

        $grid->column('created_at')->hide();
        $grid->column('updated_at')->hide();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
        $grid->disableExport();
        $grid->disableFilter();
        
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
        $show = new Show(TwillioSetting::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('twillio_sid', __('Twillio sid'));
        $show->field('twillio_auth_foken', __('Twillio auth foken'));
        $show->field('twillio_number', __('Twillio number'));
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
        $form = new Form(new TwillioSetting);

        $form->text('twillio_sid', __('Twillio sid'))->rules('required|max:250');
        $form->text('twillio_auth_foken', __('Twillio auth foken'))->rules('required|max:250');
        $form->text('twillio_number', __('Twillio number'))->rules('required|max:250');

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
