<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Tblstockout;

class TblstockoutController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock OUT LIst';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tblstockout());
        $grid->fixHeader();
        $grid->disableCreateButton();

        $grid->tools(function ($tools) {
            $count = Tblstockout::where('sku', 'like', '%' . request('sku') . '%')->count();
            $tools->append("<a href='" . config('app.url') . "/pdf' target='_blank' class='btn btn-primary'>Create PDF</a>");
            $tools->append('<a href="/admin/truncate-tblstockout" class="btn btn-danger">Delete Items</a>');
            $tools->append("<div style='padding:10px;'>Total PLTs: {$count}</div>");
        });

        $grid->rows(function ($row, $number) {
         $row->column('number', ++$number);
     });
        $grid->number('<strong>ID</strong>')->totalRow('Total Boxes');
        //$grid->column('id', __('Id'))->sortable();
        $grid->column('LCode', __('<strong>LCode</strong>'));
        $grid->column('sku', __('<strong>Sku</strong>'))->filter('like');
        $grid->column('qty', __('<strong>Qty</strong>'))->totalRow();
        $grid->column('username', __('<strong>User</strong>'));
        $grid->column('created_at', __('<strong>Created at</strong>'))->dateFormat('d-m-Y H:i:s');
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
        $show = new Show(Tblstockout::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('LCode', __('LCode'));
        $show->field('sku', __('Sku'));
        $show->field('qty', __('Qty'));
        $show->field('created_at', __('Created at'))->dateFormat('d-m-Y H:i:s');
       // $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tblstockout());

        $form->text('LCode', __('LCode'));
        $form->text('sku', __('Sku'));
        $form->text('qty', __('Qty'));

        return $form;
    }
}
