<?php

namespace App\Admin\Controllers;

use App\Status;
use App\Models\VehicleSlug;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VehicleSlugController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vehicle Slugs';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VehicleSlug());

        $grid->column('id', __('Id'));
        $grid->column('slug', __('Sluge'));
        $grid->column('icon', __('Icon'))->image();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
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
        $form = new Form(new VehicleSlug());

        $form->text('slug', __('Slug'))->rules('required|max:250');
        $form->image('icon', __('Icon'))->move('customers')->uniqueName()->rules('required');
        
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
