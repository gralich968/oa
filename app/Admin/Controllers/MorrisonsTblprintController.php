<?php

namespace App\Admin\Controllers;


use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\MorrisonsTblprint;
use App\Models\Tbldestinations;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Widgets\Table;
use App\Admin\Actions\Item\BatchDeleteByBatchNo;

class MorrisonsTblprintController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Morrisons Order Print';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
   protected function grid()
{
    $grid = new Grid(new MorrisonsTblprint());
    $grid->fixHeader();
    $grid->disableActions();
    $grid->disableExport();
    $grid->disableCreateButton();
    $grid->disableFilter();
    //$grid->disableRowSelector();

    // Create a virtual column for grouping: depo + batch_no
     $grid->model()
    ->join('tbldestinations', 'morrisons_tblprint.depo', '=', 'tbldestinations.depo_code')
    ->select(
        'morrisons_tblprint.depo',
        'morrisons_tblprint.dueDate',
        'morrisons_tblprint.batch_no',
        'tbldestinations.depo_name as depoName', // assuming this is the column holding the depo name
        DB::raw('MAX(morrisons_tblprint.id) as id')
    )
    ->groupBy('morrisons_tblprint.depo', 'tbldestinations.depo_name', 'morrisons_tblprint.dueDate', 'morrisons_tblprint.batch_no')
    ->orderBy('morrisons_tblprint.depo');

     $grid->tools(function ($tools) {
       // $tools->append("<a href='" . config('app.url') . "/import_morrisons_order' class='btn btn-primary'>Import Order</a>");
        $tools->append('<a href="/admin/truncate-morrisons-tblprint" class="btn btn-danger">Delete All</a>');
        $tools->append('<a href="/morrisons/print-picked-morrisons-order" target="_blank" class="btn btn-success">Print All</a>');
     });

     $grid->batchActions(function ($batch) {
         $batch->add(new BatchDeleteByBatchNo());
     });

      $grid->column('batch_no', __('<strong>Pallet No</strong>'))->display(function ($batch) {
        return $batch ?? 'N/A';
      })->sortable();
      $grid->column('dueDate', __('<strong>Due Date</strong>'))->display(function ($date) {
        return \Carbon\Carbon::parse($date)->format('d-m-Y');
        })->sortable();
        $grid->column('depoName', __('<strong>Depo Name</strong>'))->expand(function ($model) {
        $rows = MorrisonsTblprint::where('depo', $model->depo)
        ->whereDate('dueDate', $model->dueDate)
        ->get(['id', 'username', 'barcode', 'duedate', 'un', 'depo', 'bbdate', 'quantity', 'batch_no']);

        // Join with tbldestinations to get depo_name
        $rows = $rows->map(function ($item) {
            $depoName = Tbldestinations::where('depo_code', $item->depo)->value('depo_name');
            $prodName = DB::table('tblproducts')->where('sku', $item->barcode)->value('description');
            return [
                'ID' => $item->id,
                'Created by' => $item->username,
                'Product' => $prodName ?? $item->barcode,
                'Due Date' => $item->duedate,
                //'UN' => $item->un,
                'Depo' => $depoName ?? $item->depo,
                'BB Date' => $item->bbdate,
                'Quantity' => $item->quantity,
            ];
        });

        // Add PDF print button for this partnerRef
        $printUrl = url("/morrisons/print-picked-morrisons-depo/" . $model->depo . "?dueDate=" . $model->dueDate);
        $button = "<a href='{$printUrl}' target='_blank' class='btn btn-success btn-sm' style='margin-bottom:10px;'>Print Order</a>";

        return $button . (new Table([
            'ID', 'Created by', 'Product', 'Due Date', 'Depo', 'BB Date', 'Quantity'
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
        $show = new Show(MorrisonsTblprint::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('barcode', __('Barcode'));
        $show->field('username', __('Username'));
        $show->field('duedate', __('Due date'));
        $show->field('un', __('Un'));
        $show->field('depo', __('Depo'));
        $show->field('bbdate', __('Bbdate'));
        $show->field('quantity', __('Quantity'));
        $show->field('batch_no', __('Batch no'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new MorrisonsTblprint());

        $form->text('username', __('Username'));
        $form->text('barcode', __('Barcode'));
        $form->date('duedate', __('Due date'))->default(date('d-m-Y'));
        $form->text('un', __('Un'));
        $form->text('depo', __('Depo'));
        $form->date('bbdate', __('Bbdate'))->default(date('d-m-Y'));
        $form->number('quantity', __('Quantity'));
        $form->text('batch_no', __('Batch no'));

        return $form;
    }
}
