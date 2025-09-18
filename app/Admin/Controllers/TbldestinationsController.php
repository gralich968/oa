<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Tbldestinations;

class TbldestinationsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Destinations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tbldestinations());

        $grid->tools(function ($tools) {
        $tools->append("<a href='" . config('app.url') . "/import_destinations' class='btn btn-primary'>Import Destinations</a>");
        //$tools->append('<a href="/admin/truncate-destinations" class="btn btn-danger">Truncate Order</a>');
        });

        $grid->column('id', __('Id'));
<<<<<<< HEAD
        $grid->column('brand', __('Brand'))->display(function ($brand) {
            return strtoupper($brand);
        })->filter([
            'Morrisons' => 'MORRISONS',
            'MS' => 'MS',
        ]);
=======
        $grid->column('brand', __('Brand'));
>>>>>>> 74e963bcc8f2bf9698d0bed58c9d3b0b21d67cbe
        $grid->column('depo_name', __('Depo Name'));
        $grid->column('depo_code', __('Depo Code'));
        $grid->column('depo_type', __('Depo Type'));
        $grid->column('depo_gln', __('Depo GLN'));
        $grid->column('is_active', __('Is Active'))->using([1 => 'Active', 0 => 'Inactive'])->label([
            1 => 'success',
            0 => 'danger',
        ])->filter([
            1 => 'Active',
            0 => 'Inactive',
        ]);
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->column('deleted_at', __('Deleted at'))->hide();

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
        $show = new Show(Tbldestinations::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('brand', __('Brand'));
        $show->field('depo_name', __('Depo name'));
        $show->field('depo_code', __('Depo code'));
        $show->field('depo_type', __('Depo type'));
        $show->field('depo_gln', __('Depo gln'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
            $show->field('is_active', __('Is Active'))->as(function ($is_active) {
                return $is_active ? 'Active' : 'Inactive';
            });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tbldestinations());

        $form->text('brand', __('Brand'));
        $form->text('depo_name', __('Depo name'));
        $form->text('depo_code', __('Depo code'));
        $form->text('depo_type', __('Depo type'));
        $form->text('depo_gln', __('Depo gln'));
        $form->select('is_active', 'Is Active')->options([1 => 'Active', 0 => 'Inactive'])->default(1);

        return $form;
    }
}
