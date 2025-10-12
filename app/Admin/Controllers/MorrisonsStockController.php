<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Widgets\Table;
use App\Models\MorrisonsStock;
class MorrisonsStockController extends AdminController
{
     protected $title = 'Morrisons Stock List';

     protected function grid()

    {
        $grid = new Grid(new MorrisonsStock());

         $grid->tools(function ($tools) {
        $tools->append('<a href="/admin/truncate-morrisons-stock" class="btn btn-danger">Clear Stock</a>');
        $tools->append('<a href="/morrisons/print-stock" target="_blank" class="btn btn-success">Print Stock</a>');

     });

        $grid->column('id', 'ID')->sortable();
                // Display product description by joining with tblproducts
        $grid->column('barcode', 'Product Description')->display(function ($barcode) {
            $description = DB::table('tblproducts')
            ->where('sku', $barcode)
            ->value('description');
            return $description ?: 'N/A';
        });        
        $grid->column('sku', 'SKU')->display(function () {
    return $this->barcode ? 'SKU-' . $this->barcode : 'N/A';
});
        $grid->column('bbdate', 'BB Date')->display(function ($date) {
        return Carbon::parse($date)->format('d-m-Y');
        })->sortable();
        $grid->column('qty', 'Quantity')->sortable();
        $grid->column('updated_at', 'Last Updated')->display(function ($date) {
            return Carbon::parse($date)->toDayDateTimeString();
        });

        $grid->filter(function ($filter) {
            $filter->like('product_name', 'Product Name');
            $filter->like('sku', 'SKU');
            $filter->between('updated_at', 'Last Updated')->datetime();
        });

        return $grid;
    }
 protected function detail($id)
    {
        $show = new Show(MorrisonsStock::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('barcode', __('Barcode'));
        $show->field('bbdate', __('BB Date'));
        $show->field('qty', __('Quantity'));
        $show->field('created_at', __('Created at'))->dateFormat('d-m-Y H:i:s');
        $show->field('updated_at', __('Updated at'))->dateFormat('d-m-Y H:i:s');

        return $show;
    }

    protected function form()
    {
        $form = new Form(new MorrisonsStock());

        $form->text('barcode', __('Barcode'));
        $form->text('bbdate', __('BB Date'));
        $form->number('qty', __('Quantity'));

        return $form;
    }

}
