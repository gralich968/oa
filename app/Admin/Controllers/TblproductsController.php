<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Tblproducts;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Widgets\Box;
use App\Admin\Extensions\TblproductExporter;
use Carbon\Carbon;

class TblproductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Products Managament';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tblproducts());

        //Exporter
        //$grid->disableExport();

        //Filter
       $grid->filter(function($filter){

    // Remove the default id filter
    $filter->disableIdFilter();

    // Add a column filter
    $filter->equal('upt', 'packs in basket');

    //... additional filter options
    });

    $grid->exporter(new TblproductExporter());


   // Chart Naglowek
        $grid->header(function ($query) {
    $status = $query->select(DB::raw('count(status) as count, status'))
                ->groupBy('status')->get()->pluck('count', 'status')->toArray();
    $doughnut = view('admin.chart.status', compact('status'));
    return new Box('Status ratio', $doughnut);
});

   // FOOTER
   $grid->footer(function ($query) {

    // Query the total pages with status 1
    $data = $query->where('status', 1)->sum('status');
    $data1 = $query->where('trayod', 36)->count('id');
    $data2 = $data - $data1;

    return "<div style='padding: 15px;'>Total active :<b>$data</b> Half Active Basket :<b>$data1</b>  MT Active Basket :<b>$data2</b>";

});

//tabela
        $grid->rows(function ($row, $number) {
         $row->column('number', ++$number);
         });
        $grid->number('ID');
        $grid->column('description', __('Description'));
        $grid->column('sku', __('Sku'));
        $grid->column('upc', __('Upc'));
        $grid->column('slife', __('Slife'));
        $grid->column('trayod', __('Trayod'));
        $grid->column('upt', __('Upt'));
        $grid->column('status')->icon([
         0 => 'toggle-off',
         1 => 'toggle-on',
        ], $default = '')->sortable();
        $grid->column('bb_date', __('BB Date'))->display(function ($value) {
            // If you want to always show now() + slife, ignore $value and use $this->slife
            if ($this->slife) {
                return Carbon::now()->addDays((int)$this->slife)->format('d-m-Y');
            }
            return '';
        });
        $grid->column('created_at', __('Created at'))->dateFormat('d-m-Y H:i')->hide();
        $grid->column('updated_at', __('Updated at'))->dateFormat('d-m-Y H:i')->hide();

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
        $show = new Show(Tblproducts::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('description', __('Description'));
        $show->field('sku', __('Sku'));
        $show->field('upc', __('Upc'));
        $show->field('slife', __('Slife'));
        $show->field('trayod', __('Trayod'));
        $show->field('upt', __('Upt'));
        $show->field('status', __('Status'))->as(function ($status) {
              return $status ? 'Active' : 'Inactive';
              });
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
        $form = new Form(new Tblproducts());

        $form->text('description', __('Description'));
        $form->text('sku', __('Sku'));
        $form->text('upc', __('Upc'));
        $form->text('slife', __('Shelf Life'));
        $form->text('trayod', __('Basket on Dollies'));
        $form->text('upt', __('Upt'));
        $form->switch('status', __('Active?'));




        return $form;
    }
}
