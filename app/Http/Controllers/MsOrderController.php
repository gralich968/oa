<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mstblpicked;
use App\Models\MorrisonsTblpicked;
use App\Models\Morrisonstblorders;
use App\Models\Tbldestinations;
use Illuminate\Support\Facades\DB;

class MsOrderController extends Controller
{
    public function scaninForm() {
    $mstblpicked = Mstblpicked::all();
    $depos = Tbldestinations::all()->where('is_active', 1)->where('brand', 'MandS');
    return view('ms.pick', compact('mstblpicked', 'depos'));
}

// MS STORE SCAN ORDER
public function storescanin(Request $request) {

    $request->validate([
        'barcodes' => 'required|string',
        'username' => 'required|string',
        'duedate' => 'required|date',
        'depo' => 'required|string',
    ]);

    $barcodes = explode("\n", $request->barcodes);
    foreach ($barcodes as $barcode) {
        Mstblpicked::create([
            'barcode' => $barcode,
            'username' => $request->username,
            'duedate' => $request->duedate,
            'depo' => $request->depo,
            'un' => uniqid(),
        ]);
    }

    return back()->with('success', 'Barcodes saved!');
}

    public function storescaninsave(Request $request) {
         $companyPrefix = DB::table('tblcompany')->where('id', 1)->value('company_pref'); // Replace with actual logic

    // Generate a unique serial reference (9 digits, zero-padded)
    $serialReference = str_pad(random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
     function generateSSCC($companyPrefix, $serialReference, $extensionDigit = '0') {
        $base = $extensionDigit . $companyPrefix . $serialReference;
        $checkDigit = calculateCheckDigit($base);
        return $base . $checkDigit;
    }

    // Modulo 10 check digit calculation
    function calculateCheckDigit($number) {
        $sum = 0;
        $length = strlen($number);
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = intval($number[$i]);
            $sum += (($length - $i) % 2 === 0) ? $digit * 3 : $digit;
        }
        $mod = $sum % 10;
        return $mod === 0 ? 0 : 10 - $mod;
    }

    // Generate SSCC
    $sscc = generateSSCC($companyPrefix, $serialReference);
  // Generate one unique number for this batch
    $batchNumber = $sscc; // uniqid('batch_');

    // Get all rows from morrisons_tblpicked
    $rows = DB::table('morrisons_tblpicked')->get();


    // Map rows to new array for morrisons_tblprint
    $insertData = $rows->map(function ($row) use ($batchNumber) {
        return [
            'barcode'     => $row->barcode,
            'depo'        => $row->depo,
            'duedate'     => $row->duedate,
            'bbdate'      => $row->bbdate,
            'quantity'    => $row->quantity,
            'username'    => $row->username,
            'ponumber'    => $row->ponumber,
            'un'          => $row->un,
            'batch_no'    => $batchNumber, // same for all rows
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    })->toArray();

    // Insert into morrisons_tblprint
    DB::table('morrisons_tblprint')->insert($insertData);
    DB::table('morrisons_tblpicked')->truncate();

    return redirect()->back()->with('success', "Copied " . count($insertData) . " rows with batch number {$batchNumber}");

    }


    //MORRISONS ORDER
    public function scanMorrisonsForm() {
        $morrisonstblpicked = MorrisonsTblpicked::all();
        $depos = Tbldestinations::all()->where('is_active', 1)->where('brand', 'Morrisons');
        return view('morrisons.pick', compact('morrisonstblpicked', 'depos'));
    }

public function storeMorrisonsscan(Request $request) {
    //dd($request->all());
    $request->validate([
        'barcode' => 'required|string',
        'username' => 'required|string',
        'duedate' => 'required|date',
        'bbdate' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'depo' => 'required|string',
        'ponumber' => 'required|string',
    ]);

    Morrisonstblpicked::create([
        'barcode' => $request->barcode,
        'username' => $request->username,
        'duedate' => $request->duedate,
        'bbdate' => $request->bbdate,
        'quantity' => $request->quantity,
        'depo' => $request->depo,
        'ponumber' => $request->ponumber,
        'un' => uniqid(),
    ]);

    return back()->with('success', 'Barcode saved!');
}

public function deletePick($id)
{
    DB::table('morrisons_tblpicked')->where('id', $id)->delete();
    return redirect()->back()->with('success', 'Pick deleted successfully.');
}

public function storescanmorrisonsave(Request $request) {


    $companyPrefix = DB::table('tblcompany')->where('id', 1)->value('company_pref'); // Replace with actual logic

    // Generate a unique serial reference (9 digits, zero-padded)
    $serialReference = str_pad(random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
     function generateSSCC($companyPrefix, $serialReference, $extensionDigit = '0') {
        $base = $extensionDigit . $companyPrefix . $serialReference;
        $checkDigit = calculateCheckDigit($base);
        return $base . $checkDigit;
    }

    // Modulo 10 check digit calculation
    function calculateCheckDigit($number) {
        $sum = 0;
        $length = strlen($number);
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = intval($number[$i]);
            $sum += (($length - $i) % 2 === 0) ? $digit * 3 : $digit;
        }
        $mod = $sum % 10;
        return $mod === 0 ? 0 : 10 - $mod;
    }

    // Generate SSCC
    $sscc = generateSSCC($companyPrefix, $serialReference);
  // Generate one unique number for this batch
    $batchNumber = $sscc; // uniqid('batch_');

    // Get all rows from morrisons_tblpicked
    $rows = DB::table('morrisons_tblpicked')->get();


    // Map rows to new array for morrisons_tblprint
    $insertData = $rows->map(function ($row) use ($batchNumber) {
        return [
            'barcode'     => $row->barcode,
            'depo'        => $row->depo,
            'duedate'     => $row->duedate,
            'bbdate'      => $row->bbdate,
            'quantity'    => $row->quantity,
            'username'    => $row->username,
            'ponumber'    => $row->ponumber,
            'un'          => $row->un,
            'batch_no'    => $batchNumber, // same for all rows
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    })->toArray();

    // Insert into morrisons_tblprint
    DB::table('morrisons_tblprint')->insert($insertData);
    DB::table('morrisons_tblpicked')->truncate();

    return redirect()->back()->with('success', "Copied " . count($insertData) . " rows with batch number {$batchNumber}");
}

}
