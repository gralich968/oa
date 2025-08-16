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




