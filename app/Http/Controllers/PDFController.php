<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tblstockout;
use App\Models\Tblstockin;
use PDF;

class PDFController extends Controller
{
    public function generatePDF()
    {
		$tblstockin = Tblstockin::get();
		
		$data = [
		       'title' => 'Siema',
		       'date' => date('d/m/Y'),
		       'time' => date('H:m'),
		       'users' => $tblstockin
		
		];
		$pdf = PDF::loadView('myPDF', $data);
		return $pdf->download('stockin.pdf');
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

        $pdf = PDF::loadView('content', $data);

        return $pdf->stream('stockout.pdf');
    }

}

