<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Tblorder;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Widgets\Table;

class TblorderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order List';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    protected function grid()
    {
        $grid = new Grid(new Tblorder());
        $grid->fixHeader();
        $grid->disableActions();
        $grid->disableExport();
        // Group by partnerRef and select only necessary fields
       $grid->model()
    ->join('tbldestinations', 'tblorder.partnerRef', '=', 'tbldestinations.depo_code')
    ->select(
        'tblorder.partnerRef',
        'tbldestinations.depo_name as depoName', // assuming this is the column holding the depo name
        DB::raw('MAX(tblorder.id) as id')
    )
    ->groupBy('tblorder.partnerRef', 'tbldestinations.depo_name')
    ->orderBy('tblorder.partnerRef');

        $grid->tools(function ($tools) {
        $tools->append("<a href='" . config('app.url') . "/import_order' class='btn btn-primary'>Import Order</a>");
        $tools->append('<a href="/admin/truncate-order" class="btn btn-danger">Delete Order</a>');
        $tools->append('<a href="/admin/orders/print" target="_blank" class="btn btn-success">Print Order</a>');

     });

        $grid->column('depoName', 'Depo Name')->expand(function ($model) {

$orders = Tblorder::where('partnerRef', $model->partnerRef)
    ->orderBy('positionsposId', 'asc') // Sort by positionsposId in ascending order
    ->get();


            $rows = $orders->map(function ($order) {
            return [
                $order->positionsposId,
                $order->orderNumber,
                \Carbon\Carbon::parse($order->orderDate)->format('d-m-Y'),
                \Carbon\Carbon::parse($order->dueDate)->format('d-m-Y'),
                optional($order->product)->description,
                $order->requestQty,
                $order->sparenumber1,
            ];
            });

            // Add PDF print button for this partnerRef
            $printUrl = url("/admin/orders/print-partner/" . $model->partnerRef);
            $button = "<a href='{$printUrl}' target='_blank' class='btn btn-success btn-sm' style='margin-bottom:10px;'>Print Order</a>";

// Custom header HTML
 $sum36 = DB::table('tblorder')
            ->where('partnerRef', $model->partnerRef)
            ->join('tblproducts', 'tblorder.itemNumber', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 36)
            ->sum('tblorder.requestQty');

            $sum18 = DB::table('tblorder')
            ->where('partnerRef', $model->partnerRef)
            ->join('tblproducts', 'tblorder.itemNumber', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 18)
            ->sum('tblorder.requestQty');

            $half = $sum36 / 36;
            $full = $sum18 / 18;
            $totalhf = ceil($half + $full);
    $customHeader = "<h4 style='margin-top:10px;'>Half Tray: <strong>{$sum36}</strong> | Full Tray: <strong>{$sum18}</strong> | Total Dollies: <strong>{$totalhf}</strong></h4>";


            return $button . $customHeader . (new Table([
            'Position',
            'Order Number',
            'Order Date',
            'Due Date',
            'Product',
            'Request Qty',
            'UPT'
            ], $rows->toArray()));
        });
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
        $show = new Show(Tblorder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('companyCode', __('CompanyCode'));
        $show->field('orderNumber', __('OrderNumber'));
        $show->field('orderDate', __('OrderDate'));
        $show->field('partenerRef', __('PartenerRef'));
        $show->field('dueDate', __('DueDate'));
        $show->field('orderType', __('OrderType'));
        $show->field('positionsposId', __('PositionsposId'));
        $show->field('positioncompanyCode', __('PositioncompanyCode'));
        $show->field('itemNumber', __('ItemNumber'));
        $show->field('requestQty', __('RequestQty'));
        $show->field('positionuom', __('Positionuom'));
        $show->field('sparenuber1', __('Sparenuber1'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tblorder());

        $form->text('companyCode', __('CompanyCode'));
        $form->text('orderNumber', __('OrderNumber'));
        $form->date('orderDate', __('OrderDate'))->default(date('d-m-Y'));
        $form->text('partenerRef', __('PartenerRef'));
        $form->date('dueDate', __('DueDate'))->default(date('d-m-Y'));
        $form->text('orderType', __('OrderType'));
        $form->text('positionsposId', __('PositionsposId'));
        $form->text('positioncompanyCode', __('PositioncompanyCode'));
        $form->text('itemNumber', __('ItemNumber'));
        $form->text('requestQty', __('RequestQty'));
        $form->text('positionuom', __('Positionuom'));
        $form->text('sparenuber1', __('Sparenuber1'));

        return $form;
    }
}
