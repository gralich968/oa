<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\Tblstockin;
use App\Exports\ExportStockIn;
use Excel;

class TblstockinController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock IN List';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Tblstockin());
        $grid->fixHeader();
        $grid->disableCreateButton();

        $grid->tools(function ($tools) {
            $tools->append("<a href='" . config('app.url') . "/pdfin' target='_blank' class='btn btn-primary'>Create PDF</a>");
            $tools->append("<a href='" . config('app.url') . "/excel-export' target='_blank' class='btn btn-primary'>Export XLSX</a>");
             });

        $grid->rows(function ($row, $number) {
         $row->column('number', ++$number);
     });
        $grid->number('ID')->totalRow('Total');
        //$grid->column('id', __('Id'));
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
        $show = new Show(Tblstockin::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('LCode', __('LCode'));
        $show->field('sku', __('Sku'));
        $show->field('qty', __('Qty'));
        $show->field('created_at', __('Created at'))->dateFormat('d-m-Y H:i:s');;
        //$show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tblstockin());

        $form->text('LCode', __('LCode'));
        $form->text('sku', __('Sku'));
        $form->text('qty', __('Qty'));

        return $form;
    }

    public function excel_export()
    {
		return Excel::download(new ExportStockIn, 'stock_in.xlsx');
		}
}
