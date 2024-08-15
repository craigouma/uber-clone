<?php

namespace App\Admin\Controllers;

use App\Models\Stop;
use App\Trip;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StopController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stops';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Stop());

        $grid->column('id', __('Id'));
        $grid->column('trip_id', __('Trip Id'))->display(function($trip_id){
            return Trip::where('id',$trip_id)->value('id');
        });
        $grid->column('address', __('Address'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('longitude', __('Longitude'));
        //$grid->column('created_at', __('Created at'));
        //$grid->column('updated_at', __('Updated at'));
        
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        
        
        $grid->filter(function ($filter) {
            //Get All status
            $trips = Trip::pluck('id', 'id');
            $filter->equal('trip_id', 'Trip id')->select($trips);
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
        $form = new Form(new Stop());

        $form->number('trip_id', __('Trip Id'));
        $form->text('address', __('Address'));
        $form->text('latitude', __('Latitude'));
        $form->text('longitude', __('Longitude'));

        return $form;
    }
}
