<?php

namespace App\Admin\Controllers;

use App\Exports\ExportTblpickingsResults;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\Tblpickings;
use App\Models\TblpickingsResults;
use Illuminate\Support\Facades\DB;
use Excel;

class TblpickingsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Picking Visibility';

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
        $grid->disableExport();
        $grid->rows(function ($row, $number) {
         $row->column('number', ++$number);
         });

        $grid->tools(function ($tools) {
        $tools->append("<a href='" . config('app.url') . "/import_pickings' class='btn btn-primary'>Import Pickings</a>");
        $tools->append('<a href="/admin/truncate-pickings" class="btn btn-danger">Truncate Pickings</a>');
        $tools->append('<a href="/admin/pickings/print" target="_blank" class="btn btn-success">Print Pickings</a>');
        $tools->append('<a href="https://www.ilovepdf.com/pdf_to_excel" target="_blank" class="btn btn-info">Convert PDF to XLSX</a>');
        $tools->append("<a href='" . config('app.url') . "/upload' class='btn btn-primary'>Merge PDFs</a>");
        $tools->append("<a href='" . config('app.url') . "/pickings-export' target='_blank' class='btn btn-primary'>Export XLSX</a>");

     });


//HEADER

        // Show TraysOD summary in header above the table
        $grid->header(function ($query) {
            // Sum trayod values
            $dollies = $query->sum('trayod');
            $totalTraysOrdered = $query->sum('quantity_sum');
            $totalTraysPicked = $query->sum('picked_sum');
            $totalTraysRemaining = $query->sum('remaining');
            $divided = ceil($dollies / 64);

            // Sum quantity_sum where trayod = 36 and 18, joining tblproducts on sku
            $sum36 = DB::table('tblpickingsresult')
            ->join('tblproducts', 'tblpickingsresult.sku', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 36)
            ->sum('tblpickingsresult.quantity_sum');

            $sum18 = DB::table('tblpickingsresult')
            ->join('tblproducts', 'tblpickingsresult.sku', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 18)
            ->sum('tblpickingsresult.quantity_sum');

            return "<center><div style='margin-bottom:10px; font-weight: bold; font-size: 16px;'>
            ** Trays Ordered: {$totalTraysOrdered} | Trays Picked: {$totalTraysPicked} | <span style='color: grey;'>Trays Remaining: {$totalTraysRemaining}</span> ** | <i style='color: green;'>== Dollies: {$dollies} Tracks: {$divided} ==</i>
            <br>
            <span style='color: blue;'>Total Half Trays (trayod=36): {$sum36} | Total Metrics Trays (trayod=18): {$sum18}</span>
            </div></center>";
        });

        // Keep the column as usual



        $grid->number('<b>ID</b>')->totalRow('Total')->setAttribute('style', 'text-align: center;');
        $grid->column('product', __('<center><b>Product</b></center>'))->setAttributes(['style' => 'text-align: center;']);
        $grid->column('quantity_sum', __('<center><b>Quantity</b></center>'))->setAttributes(['style' => 'text-align: center;'])->totalRow(function ($query) {
            return "<span style='color: red; font-weight: bold;'> **Trays Ordered: $query** </span>";
        });
        $grid->column('picked_sum', __('<center><b>Picked</b></center>'))->setAttributes(['style' => 'text-align: center;'])->totalRow(function ($query) {
            return "<span style='color: red; font-weight: bold;'> **Trays Picked: $query** </span>";
        });
        $grid->column('remaining', __('<center><b>Remaining</b></center>'))->setAttributes(['style' => 'text-align: center;'])->totalRow(function ($query) {
            return "<span style='color: red; font-weight: bold;'> **Trays Remaining: $query** </span>";
        });
        //$grid->column('sku', __('SKU'));

        $grid->column('trayod', __('<center><b>Dollies</b></center>'))->setAttributes(['style' => 'text-align: center;'])->totalRow(function ($query) {
            $dollies = $query;
            $divided = ceil($query / 64);
            return "<span style='color: red; font-weight: bold;'> **Dollies: {$dollies} Tracks: {$divided}**</span>";
        });
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        // Add total for quantity_sum
       // $grid->footer(function ($query) {
       //     $totalQuantity = $query->sum('quantity_sum');
       //     $totalPicked = $query->sum('picked_sum');
       //     $totalRemaining = $query->sum('remaining');
       //     return "<tr><td colspan='2'><strong>Total Quantity:</strong></td><td><strong> {$totalQuantity}   </strong></td><td colspan='3'></td></tr>
       //             <tr><td colspan='2'><strong>Total Picked:</strong></td><td><strong> {$totalPicked}   </strong></td><td colspan='3'></td></tr>
       //             <tr><td colspan='2'><strong>Total Remaining:</strong></td><td><strong> {$totalRemaining}   </strong></td><td colspan='3'></td></tr>";
       // });

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
        $show = new Show(Tblpickings::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('position', __('Position'));
        $show->field('product', __('Product'));
        $show->field('quantity', __('Quantity'));
        $show->field('picked', __('Picked'));
        $show->field('sku', __('Sku'));
        $show->field('created_at', __('Created at'))->hide();
        $show->field('updated_at', __('Updated at'))->hide();

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tblpickings());

        $form->textarea('position', __('Position'));
        $form->textarea('product', __('Product'));
        $form->text('quantity', __('Quantity'));
        $form->text('picked', __('Picked'));
        $form->text('sku', __('Sku'));

        return $form;
    }

     public function excel_export()
    {
		return Excel::download(new ExportTblpickingsResults, 'tblpickings.xlsx');
		}
}
