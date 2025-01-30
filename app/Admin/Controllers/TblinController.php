<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Tblin;

class TblinController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tblin';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tblin());

        $grid->column('id', __('Id'));
        $grid->column('barcode', __('Barcode'));
        $grid->column('un', __('Un'));
        $grid->column('created_at', __('Created at'))->dateFormat('d-m-Y H:i:s');
        //$grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Tblin::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('barcode', __('Barcode'));
        $show->field('un', __('Un'));
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
        $form = new Form(new Tblin());

        $form->text('barcode', __('Barcode'));
        $form->text('un', __('Un'));

        return $form;
    }
}
