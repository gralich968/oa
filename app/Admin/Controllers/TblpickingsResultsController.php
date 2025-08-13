<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\TblpickingsResults;

class TblpickingsResultsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Picking Results';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TblpickingsResults());
        $grid->fixHeader();
        $grid->disableActions();

        //$grid->column('id', __('Id'));
        //$grid->column('position', __('Position'));
        $grid->column('product', __('Product'))->center();
        $grid->column('quantity_sum', __('Quantity'))->center();
        $grid->column('picked_sum', __('Picked'))->center();
        $grid->column('remaining', __('Remaining'))->center();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

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
        $show = new Show(TblpickingsResults::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('position', __('Position'));
        $show->field('product', __('Product'));
        $show->field('quantity', __('Quantity'));
        $show->field('picked', __('Picked'));
        $show->field('remain', __('Remain'));
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
        $form = new Form(new TblpickingsResults());

        $form->text('position', __('Position'));
        $form->text('product', __('Product'));
        $form->text('quantity', __('Quantity'));
        $form->text('picked', __('Picked'));
        $form->text('remain', __('Remain'));

        return $form;
    }
}
