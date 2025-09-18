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
    protected $title = 'List Scaning IN';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tblin());
        $grid->tools(function ($tools) {
            $tools->append('<a href="/admin/truncate-tblin" class="btn btn-danger">Delete Scanned</a>');
        });

        $grid->column('id', __('<strong>ID</strong>'));
        $grid->column('barcode', __('<strong>Barcode</strong>'));
        $grid->column('username', __('<strong>User</strong>'));
        $grid->column('un', __('<strong>Un</strong>'));
        $grid->column('created_at', __('<strong>Created at</strong>'))->dateFormat('d-m-Y H:i:s');
        $grid->column('updated_at', __('<strong>Updated at</strong>'))->dateFormat('d-m-Y H:i:s')->hide();

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
        $show->field('username', __('User'));
        $show->field('un', __('Un'));
        $show->field('created_at', __('Created at'))->dateFormat('d-m-Y H:i:s');
        $show->field('updated_at', __('Updated at'))->dateFormat('d-m-Y H:i:s');

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
