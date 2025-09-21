<?php

namespace App\Http\Controllers;

use App\Models\MorrisonsTblorders;
use App\Models\MorrisonsTblprint;
use App\Models\Tblcompany;
use App\Models\Tbldestinations;
use App\Models\Tblorder;
use App\Models\TblpickingsResults;
use App\Models\Tblproducts;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

   public function printMorrisonsOrders()
{

$partnerRefs = MorrisonsTblorders::select('partnerRef')->distinct()->get();
$groupedOrders = MorrisonsTblorders::select('partnerRef', 'dueDate')
    ->distinct()
    ->orderBy('dueDate')
    ->get();

$generatorHTML = new BarcodeGeneratorPNG();


$data = $groupedOrders->map(function ($group) use ($generatorHTML) {
    $orders = MorrisonsTblorders::where('partnerRef', $group->partnerRef)
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



    $pdf = PDF::loadView('admin.orders.printmorrisons', compact('data'));
    return $pdf->stream('depo-orders.pdf');
}

public function printPartnerMorrisons(Request $request, $partnerRef)
{


    $dueDate = $request->query('dueDate'); // Get dueDate from query string
    $generatorHTML = new BarcodeGeneratorPNG();

    // Get all orders for the partner
    $orders = MorrisonsTblorders::where('partnerRef', $partnerRef)
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
    $pdf = Pdf::loadView('admin.orders.print-partner-morrisons', ['data' => $data]);
    return $pdf->stream('partner-orders-morrisons' . now() . '.pdf');
}

public function PrintPickedMorrisonsDepo(Request $request, $depo)
{
    $dueDate = MorrisonsTblprint::where('depo', $depo)->value('dueDate'); // Get dueDate from query string
    $generatorHTML = new BarcodeGeneratorPNG();

    // Get all picked orders for the depo
    $orders = MorrisonsTblprint::where('depo', $depo)
        ->whereDate('dueDate', $dueDate)
        ->where('quantity', '>', 0) // Only include picked items
        ->orderBy('barcode', 'asc')
        ->get();

    // Group orders by dueDate
    $groupedOrders = $orders->groupBy(function ($order) {
        return $order->dueDate;
    });

   $data = $groupedOrders->map(function ($ordersGroup, $dueDate) use ($depo, $generatorHTML) {
    $poNumber = $ordersGroup->first()?->ponumber ?? '000000';
    $depoName = Tbldestinations::where('depo_code', $depo)->value('depo_name');

    // Get the barcode from the first order in the group
   $barcode = $ordersGroup->first()?->barcode;

$product = Tblproducts::join('morrisons_tblprint', 'tblproducts.sku', '=', 'morrisons_tblprint.barcode')
    ->where('tblproducts.sku', $barcode)
    ->select('tblproducts.upt', 'tblproducts.description')
    ->first();

$upt = $product->upt ?? 'N/A';
$desc = $product->description ?? 'N/A';

    $companyCode = MorrisonsTblorders::where('partnerRef', $depo)->value('companyCode');
    $batch = $ordersGroup->first()?->batch_no ?? 'N/A';
    $dueDate = MorrisonsTblprint::where('depo', $depo)->value('dueDate');
    $companyPrefix = Tblcompany::pluck('company_pref')->first();
    $barcodeImg = base64_encode($generatorHTML->getBarcode($batch, $generatorHTML::TYPE_CODE_39));

    return [
        'orders'        => $ordersGroup,
        'partnerRef'    => $depo,
        'poNumber'      => $poNumber,
        'depoName'      => $depoName,
        'description'   => $desc,
        'dueDate'       => $dueDate,
        'barcode'       => $barcodeImg,
        'batch_no'      => $batch,
        'product'       => $product,
        'companyCode'   => $companyCode,
        'companyPrefix' => $companyPrefix,
    ];
});


    // Render PDF with grouped data
    //dd($data);
    $pdf = Pdf::loadView('morrisons.print-picked-morrisons-depo', ['data' => $data]);
    return $pdf->stream('picked-morrisons-depo-' . now() . '.pdf');

}

public function PrintPickedMorrisonsOrder(Request $request)
{
    $pickedOrders = MorrisonsTblprint::where('quantity', '>', 0)
        ->orderBy('depo', 'asc')
        ->orderBy('dueDate', 'asc')
        ->get();

    $groupedOrders = $pickedOrders->groupBy(function ($order) {
        return $order->depo . '|' . $order->dueDate; // Group by depo and dueDate
    });

    $generatorHTML = new BarcodeGeneratorPNG();

    $data = $groupedOrders->map(function ($ordersGroup, $key) use ($generatorHTML) {
        list($depo, $dueDate) = explode('|', $key);
        $poNumber = $ordersGroup->first()?->ponumber ?? '000000';
        $depoName = Tbldestinations::where('depo_code', $depo)->value('depo_name');

        // Get the barcode from the first order in the group
       $barcode = $ordersGroup->first()?->barcode;

    $product = Tblproducts::join('morrisons_tblprint', 'tblproducts.sku', '=', 'morrisons_tblprint.barcode')
        ->where('tblproducts.sku', $barcode)
        ->select('tblproducts.upt', 'tblproducts.description')
        ->first();

    $upt = $product->upt ?? 'N/A';
    $desc = $product->description ?? 'N/A';

        $companyCode = MorrisonsTblorders::where('partnerRef', $depo)->value('companyCode');
        $batch = $ordersGroup->first()?->batch_no ?? 'N/A';
        $dueDateFormatted = \Carbon\Carbon::parse($dueDate)->format('d-m-Y');
        $companyPrefix = Tblcompany::pluck('company_pref')->first();
        $barcodeImg = base64_encode($generatorHTML->getBarcode($batch, $generatorHTML::TYPE_CODE_39));

        return [
            'orders'        => $ordersGroup,
            'partnerRef'    => $depo,
            'poNumber'      => $poNumber,
            'depoName'      => $depoName,
            'description'   => $desc,
            'dueDate'       => $dueDateFormatted,
            'barcode'       => $barcodeImg,
            'batch_no'      => $batch,
            'product'       => $product,
            'companyCode'   => $companyCode,
            'companyPrefix' => $companyPrefix,
        ];
    });

    // Render PDF with grouped data
    $pdf = Pdf::loadView('morrisons.print-picked-morrisons-order', ['data' => $data]);
    return $pdf->stream('picked-morrisons-order-' . now() . '.pdf');
}

}
