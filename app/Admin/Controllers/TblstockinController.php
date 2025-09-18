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
            $count = Tblstockin::where('sku', 'like', '%' . request('sku') . '%')->count();
            $tools->append("<a href='" . config('app.url') . "/pdfin' target='_blank' class='btn btn-primary'>Create PDF</a>");
            $tools->append("<a href='" . config('app.url') . "/excel-export' target='_blank' class='btn btn-primary'>Export XLSX</a>");
            $tools->append('<a href="/admin/truncate-tblstockin" class="btn btn-danger">Delete Items</a>');
            $tools->append("<div style='padding:10px;'>Total PLTs: {$count}</div>");
             });


        $grid->filter(function ($filter) {
            $filter->like('sku', 'SKU');
            $filter->equal('username', 'User')->select(
                Tblstockin::distinct()->pluck('username', 'username')
            );
        });


        $grid->rows(function ($row, $number) {
         $row->column('number', ++$number);
        });
        $grid->number('<strong>ID</strong>')->totalRow('Total Boxes');
        //$grid->column('id', __('Id'));
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
