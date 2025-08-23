<?php

namespace App\Http\Controllers;

use App\Models\Tbldestinations;
use App\Models\Tblorder;
use App\Models\TblpickingsResults;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintOrdersController extends Controller
{

   public function printDepoOrders()
{

$partnerRefs = Tblorder::select('partnerRef')->distinct()->get();
$groupedOrders = Tblorder::select('partnerRef', 'dueDate')
    ->distinct()
    ->orderBy('dueDate')
    ->get();

$generatorHTML = new BarcodeGeneratorPNG();


$data = $groupedOrders->map(function ($group) use ($generatorHTML) {
    $orders = Tblorder::where('partnerRef', $group->partnerRef)
        ->whereDate('dueDate', $group->dueDate)
        ->orderBy('positionsposId', 'asc')
        ->get();

    $poNumber = $orders->first()?->orderNumber ?? '000000';



    return [
        'depoName' => Tbldestinations::where('depo_code', $group->partnerRef)->value('depo_name'),
        'orders' => $orders,
        'dueDate' => \Carbon\Carbon::parse($group->dueDate)->format('d-m-Y'),
        'poNumber' => $poNumber,
        'barcode' => base64_encode($generatorHTML->getBarcode('400' . $poNumber, $generatorHTML::TYPE_CODE_39)),
    ];
});



    $pdf = PDF::loadView('admin.orders.print', compact('data'));
    return $pdf->stream('depo-orders-' . now() . '.pdf');
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



public function printPartner(Request $request, $partnerRef)
{


    $dueDate = $request->query('dueDate'); // Get dueDate from query string
    $generatorHTML = new BarcodeGeneratorPNG();

    // Get all orders for the partner
    $orders = Tblorder::where('partnerRef', $partnerRef)
        ->whereDate('dueDate', $dueDate)
        ->orderBy('positionsposId', 'asc')
        ->get();

    // Group orders by dueDate
    $groupedOrders = $orders->groupBy(function ($order) {
        return \Carbon\Carbon::parse($order->dueDate)->format('d-m-Y');
    });

    // Prepare data for each dueDate group
    $data = $groupedOrders->map(function ($ordersGroup, $dueDate) use ($partnerRef, $generatorHTML) {
        $poNumber = $ordersGroup->first()?->orderNumber ?? '000000';
        $depoName = Tbldestinations::where('depo_code', $partnerRef)->value('depo_name');
        $barcode = base64_encode($generatorHTML->getBarcode('400' . $poNumber, $generatorHTML::TYPE_CODE_39));

        return [
            'orders' => $ordersGroup,
            'partnerRef' => $partnerRef,
            'poNumber' => $poNumber,
            'depoName' => $depoName,
            'dueDate' => $dueDate,
            'barcode' => $barcode,
        ];
    });

    // Render PDF with grouped data
    //dd($data);
    $pdf = Pdf::loadView('admin.orders.print-partner', ['data' => $data]);
    return $pdf->stream('partner-orders-' . now() . '.pdf');
}



}
