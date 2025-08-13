<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tblstockout;
use App\Models\Tblstockin;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use PDF;


class PDFController extends Controller
{
    public function generatePDF()
    {
		$tblstockin = Tblstockin::get();

		$data = [
		       'title' => 'STOCK IN LIST',
		       'date' => date('d/m/Y'),
		       'time' => date('H:m'),
		       'users' => $tblstockin

		];
		$pdf = FacadePdf::loadView('myPDF', $data);
		return $pdf->stream('stockin.pdf');
		}

		public function viewPdf()
    {
		$tblstockout = Tblstockout::get();
        $data = [
		       'title' => 'Siema',
		       'date' => date('d/m/Y'),
		       'time' => date('H:m'),
		       'users' => $tblstockout

		];

        $pdf = FacadePdf::loadView('content', $data);

        return $pdf->stream('stockout.pdf');
    }


public function merge(Request $request)
{
    $request->validate([
        'pdfs.*' => 'required|file|mimes:pdf',
    ]);

    $pdfMerger = PdfMerger::init();

    foreach ($request->file('pdfs') as $pdf) {
        $pdfMerger->addPDF($pdf->getPathname(), 'all');
    }

    $mergedPath = public_path('pdfs/merged_' . time() . '.pdf');
    $pdfMerger->merge();
    $pdfMerger->save($mergedPath);

    return response()->download($mergedPath)->deleteFileAfterSend(false);
    // Optionally, you can redirect back with a success message
    return redirect()->back()->with('success', 'PDFs merged successfully!');

}

}
