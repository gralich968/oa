<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Admin\Controllers\TblstockinController;
use App\Admin\Controllers\AuthController;
use App\Http\Controllers\ImportDestinationsController;
use App\Http\Controllers\ImportOrderController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;

Admin::routes();

Route::get('/', [AuthController::class, 'login']);

Route::get('pdf', [PDFController::class, 'viewPdf']);
Route::get('pdfin', [PDFController::class, 'generatePDF']);
Route::get('excel-export', [TblstockinController::class, 'excel_export']);
Route::get('/import_order', [ImportOrderController::class, 'index']);
Route::post('/import_order/import', [ImportOrderController::class, 'import']);
Route::get('admin/truncate-order', function () {
    DB::table('tblorder')->truncate();
    admin_toastr('Table truncated!', 'success');
    return redirect()->refresh();
});
Route::get('admin/truncate-destinations', function () {
    DB::table('tbldestinations')->truncate();
    admin_toastr('Table truncated!', 'success');
    return redirect()->refresh();
});
Route::get('/import_destinations', [ImportDestinationsController::class, 'index']);
Route::post('/import_destinations/import', [ImportDestinationsController::class, 'import']);
