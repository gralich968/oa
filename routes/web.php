<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Admin\Controllers\TblstockinController;
use App\Admin\Controllers\AuthController;
use App\Admin\Controllers\TblpickingsController;
use App\Http\Controllers\ImportDestinationsController;
use App\Http\Controllers\ImportOrderController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PrintOrdersController;
use App\Http\Controllers\ImportPickingsController;
use App\Http\Controllers\BarcodeController;



Admin::routes();

Route::get('/', [AuthController::class, 'login']);
Route::get('pdf', [PDFController::class, 'viewPdf']);
Route::get('pdfin', [PDFController::class, 'generatePDF']);
Route::get('excel-export', [TblstockinController::class, 'excel_export']);
Route::get('pickings-export', [TblpickingsController::class, 'excel_export']);


Route::get('admin/truncate-order', function () {
    DB::table('tblorder')->truncate();
    admin_toastr('Table truncated!', 'success');
    return redirect(admin_url('tblorders'));
});
Route::get('/import_order', [ImportOrderController::class, 'index']);
Route::post('/import_order/import', [ImportOrderController::class, 'import']);
Route::get('/admin/orders/print', [PrintOrdersController::class, 'printDepoOrders']);


Route::get('admin/truncate-destinations', function () {
    DB::table('tbldestinations')->truncate();
    admin_toastr('Table truncated!', 'success');
    return redirect('admin/tbldestinations');
});
Route::get('/import_destinations', [ImportDestinationsController::class, 'index']);
Route::post('/import_destinations/import', [ImportDestinationsController::class, 'import']);


Route::get('admin/truncate-pickings', function () {
    DB::table('tblpickings')->truncate();
    DB::table('tblpickingsresult')->truncate();
    admin_toastr('Tblpickings and Tblpickingsresults truncated!', 'success');
    return redirect('admin/tblpickings');
});
Route::get('/import_pickings', [ImportPickingsController::class, 'index']);
Route::post('/import_pickings/import', [ImportPickingsController::class, 'import']);
Route::get('/admin/orders/print', [PrintOrdersController::class, 'printDepoOrders']);
Route::get('/admin/pickings/print', [PrintOrdersController::class, 'printPickingList']);


Route::get('/upload', function () {
    return view('upload');
});
Route::post('/upload', [PDFController::class, 'merge'])->name('pdf.merge');
Route::get('/admin/orders/print-partner/{partnerRef}', [PrintOrdersController::class, 'printPartner']);

Route::get('scanin', function () {
    return view('stockin.index');
});
//STOCK
Route::get('admin/truncate-tblin', function () {
    DB::table('tblin')->truncate();
    admin_toastr('Tblin truncated!', 'success');
    return redirect('admin/tblin');
});
Route::get('admin/truncate-tblstockin', function () {
    DB::table('tblstockin')->truncate();
    admin_toastr('Tblstockin truncated!', 'success');
    return redirect('admin/tblstockin');
});

Route::get('admin/truncate-tblout', function () {
    DB::table('tblout')->truncate();
    admin_toastr('Tblout truncated!', 'success');
    return redirect('admin/tblout');
});
Route::get('admin/truncate-tblstockout', function () {
    DB::table('tblstockout')->truncate();
    admin_toastr('Tblstockout truncated!', 'success');
    return redirect('admin/tblstockout');
});
Route::get('/stock/scanin', [BarcodeController::class, 'scaninForm']);
Route::post('/stock/scanin', [BarcodeController::class, 'storescanin']);
Route::post('/stock/scaninsave', [BarcodeController::class, 'storescaninsave']);

Route::get('/stock/scanout', [BarcodeController::class, 'scanoutForm']);
Route::post('/stock/scanout', [BarcodeController::class, 'storescanout']);
Route::post('/stock/scanoutsave', [BarcodeController::class, 'storescanoutsave']);

//END STOCK

// MS ORDER
Route::get('/ms/pick', [App\Http\Controllers\MsOrderController::class, 'scaninForm']);
Route::post('/ms/pick', [App\Http\Controllers\MsOrderController::class, 'storescanin']);
Route::post('/ms/picksave', [App\Http\Controllers\MsOrderController::class, 'storescaninsave']);
//END MS ORDER

//MORRISONS ORDER
Route::get('/import_morrisons_order', [ImportOrderController::class, 'morrisonsIndex']);
Route::post('/import_morrisons_order/import', [ImportOrderController::class, 'importMorrisons']);
Route::get('/admin/morrisons-orders/printmorrisons', [PrintOrdersController::class, 'printMorrisonsOrders']);
Route::get('/admin/orders/print-partner-morrisons/{depo}', [PrintOrdersController::class, 'printPartnerMorrisons']);
Route::get('/morrisons/pick', [App\Http\Controllers\MsOrderController::class, 'scanMorrisonsForm']);
Route::post('/morrisons/pick', [App\Http\Controllers\MsOrderController::class, 'storeMorrisonsscan']);
Route::post('/morrisons/picksave', [App\Http\Controllers\MsOrderController::class, 'storescanmorrisonsave']);
Route::post('/morrisons/deletepick/{id}', [App\Http\Controllers\MsOrderController::class, 'deletePick']);
Route::get('/morrisons/print-picked-morrisons-depo/{depo}', [PrintOrdersController::class, 'PrintPickedMorrisonsDepo']);
Route::get('/morrisons/print-picked-morrisons-order', [PrintOrdersController::class, 'PrintPickedMorrisonsOrder']);

Route::get('admin/truncate-morrisons-order', function () {
    DB::table('morrisons_tblorders')->truncate();
    admin_toastr('All Morrisons Orders DELETED!', 'success');
    return redirect(admin_url('morrisons-tblorders'));
});

Route::get('admin/truncate-morrisons-tblprint', function () {
    DB::table('morrisons_tblprint')->truncate();
    admin_toastr('Pallets Ready to Print DELETED!', 'success');
    return redirect(admin_url('morrisons-tblprints'));
});
//END MORRISONS ORDER




