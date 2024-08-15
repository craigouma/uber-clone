<?php

namespace App\Admin\Controllers;

use App\DriverRecharge;
use App\DriverWalletHistory;
use App\Driver;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverRechargeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Driver Recharge';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverRecharge);

        $grid->column('id', __('Id'));
        $grid->column('driver_id', __('Driver'))->display(function ($driver_id) {
            $driver_name = Driver::where('id', $driver_id)->value('first_name');
            return $driver_name;
        });
        $grid->column('amount', __('Amount'));
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();



        $grid->disableRowSelector();
        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });



        $grid->filter(function ($filter) {
            $drivers = Driver::pluck('first_name', 'id');
            $filter->disableIdFilter();
            $filter->equal('driver_id', 'Driver')->select($drivers);
        });


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
        $show = new Show(ComplaintSubCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('complaint_category', __('Complaint category'));
        $show->field('complaint_sub_category_name', __('Complaint sub category name'));
        $show->field('status', __('Status'));
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
        $form = new Form(new DriverRecharge);

        $drivers = Driver::pluck('first_name', 'id');

        $form->select('driver_id', 'Driver')->options($drivers)->rules(function ($form) {
            return 'required';
        });
        $form->number('amount', __('Amount'))->min(1);
        $form->saved(function (Form $form) {
            $this->update_wallet($form->model()->driver_id, $form->model()->amount);
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

    public function update_wallet($id, $amount)
    {
        $data['driver_id'] = $id;
        $data['type'] = 1;
        $data['message'] = "Added to wallet";
        $data['message_ar'] = "تمت إضافته إلى المحفظة";
        $data['amount'] = $amount;
        DriverWalletHistory::create($data);

        $old_wallet = Driver::where('id', $id)->value('wallet');
        $new_wallet = $old_wallet + $amount;
        Driver::where('id', $id)->update(['wallet' => $new_wallet]);

        return 1;
    }
}
