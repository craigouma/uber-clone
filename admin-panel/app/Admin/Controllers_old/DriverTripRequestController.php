<?php

namespace App\Admin\Controllers;

use App\Models\TripRequestStatus;
use App\Driver;
use App\TripRequest;
use App\Models\DriverTripRequest;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverTripRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Trip Request';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverTripRequest());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver Id'))->display(function($driver_id){
            return Driver::where('id',$driver_id)->value('first_name');
        });
        
        $grid->column('trip_request_id', __('Trip Request Id'))->display(function($trip_request_id){
            return TripRequest::where('id',$trip_request_id)->value('pickup_address');
        });
        $grid->column('status', __('Status'))->display(function($status){
            $name = TripRequestStatus::where('id',$status)->value('status');
            if ($status == 1) {
                return "<span class='label label-success'>$name</span>";
            } else {
                return "<span class='label label-danger'>$name</span>";
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
            //Get All status
            $statuses = TripRequestStatus::pluck('status', 'id');
            $trip_requests = TripRequest::pluck('pickup_address', 'id');
            $drivers = Driver::pluck('first_name', 'id');

            $filter->equal('trip_request_id', 'Trip request id')->select($trip_requests);
            $filter->equal('driver_id', 'Driver id')->select($drivers);
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
        $form = new Form(new DriverTripRequest());

        $statuses = TripRequestStatus::pluck('status', 'id');
        $trip_requests = TripRequest::pluck('pickup_address', 'id');
        $drivers = Driver::pluck('first_name', 'id');

        $form->select('driver_id', __('Driver Id'))->rules(function ($form) {
            return 'required';
        });
        $form->select('trip_request_id', __('Trip Request Id'))->rules(function ($form) {
            return 'required';
        });
         $form->select('status', __('Status'))->options($statuses)->default(1)->rules(function ($form) {
            return 'required';
        });
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
