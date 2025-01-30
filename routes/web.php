<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('pdf', [PDFController::class, 'viewPdf']);
Route::get('pdfin', [PDFController::class, 'generatePDF']);
