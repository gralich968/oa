<?php

namespace App\Http\Controllers;

use App\Models\Tbldestinations;
use App\Models\Tblorder;
use App\Models\TblpickingsResults;
use PDF;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PrintOrdersController extends Controller
{

   public function printDepoOrders()
{

$partnerRefs = Tblorder::select('partnerRef')->distinct()->get();

$generatorHTML = new BarcodeGeneratorPNG();

$data = $partnerRefs->map(function ($ref) use ($generatorHTML) {
    $poNumber = Tblorder::where('partnerRef', $ref->partnerRef)->value('orderNumber');

    return [
        'depoName' => Tbldestinations::where('depo_code', $ref->partnerRef)->value('depo_name'),
        'orders' => Tblorder::where('partnerRef', $ref->partnerRef)->get(),
        'dueDate' => Tblorder::where('partnerRef', $ref->partnerRef)
            ->orderByDesc('dueDate')
            ->value('dueDate'),
        'poNumber' => $poNumber,
        'barcode' => base64_encode($generatorHTML->getBarcode('400'.$poNumber, $generatorHTML::TYPE_CODE_39)),
    ];




    });

    $pdf = PDF::loadView('admin.orders.print', compact('data'));
    return $pdf->stream('depo-orders.pdf');
}

public function printPickingList()
{
    $data = TblpickingsResults::select('id', 'product', 'quantity_sum', 'picked_sum', 'remaining', 'sku', 'trayod')
            ->where('quantity_sum', '>', 0)
            ->get();

    $totals = [
        'tracks' => ceil($data->sum('trayod') / 64),
        'dollies_sum' => $data->sum('trayod'),
        'quantity_sum' => $data->sum('quantity_sum'),
        'picked_sum' => $data->sum('picked_sum'),
        'remaining' => $data->sum('remaining'),
            ];

    $pdf = PDF::loadView('admin.pickings.print', compact('data', 'totals'));
    return $pdf->stream('picking-list.pdf');
}


}
