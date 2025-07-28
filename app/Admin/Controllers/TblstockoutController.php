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
    protected $title = 'Tblstockout';

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
    $tools->append("<a href='http://localhost:8000/pdf' class='btn btn-secondary'>Create PDF</a>");
     });

        $grid->rows(function ($row, $number) {
         $row->column('number', ++$number);
     });
        $grid->number('ID')->totalRow('Total Boxes'); 
        //$grid->column('id', __('Id'))->sortable();
        $grid->column('LCode', __('LCode'));
        $grid->column('sku', __('Sku'))->filter('like');
        $grid->column('qty', __('Qty'))->totalRow();
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
