<?php

namespace App\Admin\Controllers;

use App\DriverWalletHistory;
use App\Driver;
use App\Status;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverWalletHistoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Wallet History';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverWalletHistory);

        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver name'))->display(function ($driver_id) {
            return Driver::where('id', $driver_id)->value('first_name');
        });
        $grid->column('message', __('Message'));
        $grid->column('amount', __('Amount'));



        $grid->disableRowSelector();
        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });


        $grid->filter(function ($filter) {
            //Get All status
            $drivers = Driver::pluck('first_name', 'id');

            $filter->like('message', 'Message');
            $filter->like('driver_id', 'Driver')->select($drivers);
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
        $form = new Form(new DriverWalletHistory);
        $driver = Driver::pluck('first_name', 'id');

        $form->select('driver_id', __('Driver name'))->options($driver)->rules(function ($form) {
            return 'required';
        });
        $form->text('message', __('Message'))->rules(function ($form) {
            return 'required';
        });
        $form->select('type', __('Type'))->options(['1' => 'Credit', '2' => 'Debit'])->rules(function ($form) {
            return 'required';
        });
        $form->decimal('amount', __('Amount'))->rules(function ($form) {
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
