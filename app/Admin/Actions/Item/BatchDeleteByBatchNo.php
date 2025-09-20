<?php

namespace App\Admin\Actions\Item;

use OpenAdmin\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\MorrisonsTblprint;




class BatchDeleteByBatchNo extends BatchAction
{
    public $name = 'Delete Grouped by Batch No';

    public function handle(Collection $collection, Request $request)
    {
        // Get all batch_no values from selected rows
        $batchNos = $collection->pluck('batch_no')->unique();

        // Delete all items that match any of the selected batch_no values
        MorrisonsTblprint::whereIn('batch_no', $batchNos)->delete();

        return $this->response()->success("Deleted items grouped by " . $batchNos->implode(', ') . ".")->refresh();
    }
}



