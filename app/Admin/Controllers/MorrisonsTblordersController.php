<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\MorrisonsTblorders;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Widgets\Table;

class MorrisonsTblordersController extends AdminController
{ /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Morrisons Order List';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    protected function grid()
    {
        $grid = new Grid(new MorrisonsTblorders());
        $grid->fixHeader();
        $grid->disableActions();
        $grid->disableExport();
        $grid->disableRowSelector();
        // Group by partnerRef and select only necessary fields
       $grid->model()
    ->join('tbldestinations', 'morrisons_tblorders.partnerRef', '=', 'tbldestinations.depo_code')
    ->select(
        'morrisons_tblorders.partnerRef',
        'morrisons_tblorders.dueDate',
        'tbldestinations.depo_name as depoName', // assuming this is the column holding the depo name
        DB::raw('MAX(morrisons_tblorders.id) as id')
    )
    ->groupBy('morrisons_tblorders.partnerRef', 'tbldestinations.depo_name', 'morrisons_tblorders.dueDate')
    ->orderBy('morrisons_tblorders.partnerRef');

        $grid->tools(function ($tools) {
        $tools->append("<a href='" . config('app.url') . "/import_morrisons_order' class='btn btn-primary'>Import Order</a>");
        $tools->append('<a href="/admin/truncate-morrisons-order" class="btn btn-danger">Delete Order</a>');
        $tools->append('<a href="/admin/morrisons-orders/printmorrisons" target="_blank" class="btn btn-success">Print Order</a>');

     });

        $grid->column('dueDate', __('<strong>Due Date</strong>'))->display(function ($date) {
        return \Carbon\Carbon::parse($date)->format('d-m-Y');
        })->sortable();

        //$grid->column('partnerRef', __('<strong>Depo Code</strong>'));
        $grid->column('depoName', __('<strong>Depo Name</strong>'))->expand(function ($model) {
        $orders = MorrisonsTblorders::where('partnerRef', $model->partnerRef)
        ->whereDate('dueDate', $model->dueDate)
        ->orderBy('positionsposId', 'asc')
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
                //optional($order->product) && $order->product->slife
                //    ? \Carbon\Carbon::now()->addDays($order->product->slife)->format('d-m-Y')
                //    : null
               ];
            });

            // Add PDF print button for this partnerRef
            $printUrl = url("/admin/orders/print-partner-morrisons/" . $model->partnerRef . "?dueDate=" . $model->dueDate);
            $button = "<a href='{$printUrl}' target='_blank' class='btn btn-success btn-sm' style='margin-bottom:10px;'>Print Order</a>";

// Custom header HTML
$groupedSums = DB::table('morrisons_tblorders')
    ->join('tblproducts', 'morrisons_tblorders.itemNumber', '=', 'tblproducts.sku')
    ->selectRaw('morrisons_tblorders.dueDate,
                 SUM(CASE WHEN tblproducts.trayod = 36 THEN morrisons_tblorders.requestQty ELSE 0 END) as sum36,
                 SUM(CASE WHEN tblproducts.trayod = 18 THEN morrisons_tblorders.requestQty ELSE 0 END) as sum18')
    ->where('morrisons_tblorders.partnerRef', $model->partnerRef)
    ->whereDate('morrisons_tblorders.dueDate', $model->dueDate)
    ->groupBy('morrisons_tblorders.dueDate')
    ->orderBy('morrisons_tblorders.dueDate')
    ->get();


    $customHeader = '';

foreach ($groupedSums as $group) {
    $half = $group->sum36 / 36;
    $full = $group->sum18 / 18;
    $totalhf = ceil($half + $full);
    $dueDate = \Carbon\Carbon::parse($group->dueDate)->format('d-m-Y');

    $customHeader .= "<h4 style='margin-top:10px;'>
        Due Date: <strong>{$dueDate}</strong><br>
        Order Number: <strong>" . ($orders->first()?->orderNumber ?? '000000') . "</strong><br>
    </h4>";
}

            return $button . $customHeader . (new Table([
            'Position',
            'Order Number',
            'Order Date',
            'Due Date',
            'Product',
            'Request Qty',
            'UPT',
           // 'BB Date'
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
        $show = new Show(MorrisonsTblorders::findOrFail($id));

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
        $form = new Form(new MorrisonsTblorders());

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
