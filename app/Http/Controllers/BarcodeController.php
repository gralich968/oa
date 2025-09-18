<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tblin;
use App\Models\Tblout;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
// Stock Management
// Stock IN
{
public function scaninForm() {
    $tblin = Tblin::all();
    return view('stock.scanin', compact('tblin'));
}

public function storescanin(Request $request) {
    $request->validate([
        'barcodes' => 'required|string',
        'username' => 'required|string',
    ]);

    $barcodes = explode("\n", $request->barcodes);
    foreach ($barcodes as $barcode) {
        Tblin::create([
            'barcode' => trim($barcode),
            'username' => $request->username,
            'un' => uniqid(),
        ]);
    }

    return back()->with('success', 'Barcodes saved!');
}

public function storescaninsave(Request $request)
{

    // Fetch ll records from tblin
    $records = DB::table('tblin')->get();

    // Prepare transformed records
    $transformedRecords = $records->map(function ($record) {
        // Trim leading zeros from barcode
        $barcode = ltrim($record->barcode, '0');

        // Extract segments
        $sku = substr($barcode, 0, 5);      // 0 to 5 (5 characters)
        $lcode = substr($barcode, 5, 5);    // 6 to 10 (5 characters)
        $qty = ltrim(substr($barcode, -3), '0');     // 11 to 13 (3 characters)

        return [
            'sku' => $sku,
            'LCode' => $lcode,
            'qty' => $qty,
            'username' => $record->username,
            'un' => $record->un,
            'created_at' => $record->created_at,
            'updated_at' => now(),
            // Add other fields from tblin if needed
        ];
    });

    // Insert into tblstockin
    DB::table('tblstockin')->insert($transformedRecords->toArray());

    // Clear tblin
    DB::table('tblin')->truncate();

    return back()->with('success', 'Data moved to stock!');
}

// Stock OUT
public function scanoutForm() {
    $tblout = Tblout::all();
    return view('stock.scanout', compact('tblout'));
}

public function storescanout(Request $request) {
    $request->validate([
        'barcodes' => 'required|string',
        'username' => 'required|string',
    ]);

    $barcodes = explode("\n", $request->barcodes);
    foreach ($barcodes as $barcode) {
        Tblout::create([
            'barcode' => trim($barcode),
            'username' => $request->username,
            'un' => uniqid(),
        ]);
    }

    return back()->with('success', 'Barcodes saved!');
}

public function storescanoutsave(Request $request)
{

    // Fetch all records from tblout
    $records = DB::table('tblout')->get();

    // Prepare transformed records
    $transformedRecords = $records->map(function ($record) {
        // Trim leading zeros from barcode
        $barcode = ltrim($record->barcode, '0');

        // Extract segments
        $sku = substr($barcode, 0, 5);      // 0 to 5 (5 characters)
        $lcode = substr($barcode, 5, 5);    // 6 to 10 (5 characters)
        $qty = ltrim(substr($barcode, -3), '0');     // 11 to 13 (3 characters)

        return [
            'sku' => $sku,
            'LCode' => $lcode,
            'qty' => $qty,
            'username' => $record->username,
            'un' => $record->un,
            'created_at' => $record->created_at,
            'updated_at' => now(),
            // Add other fields from tblin if needed
        ];
    });

    // Insert into tblstockout
    DB::table('tblstockout')->insert($transformedRecords->toArray());

    // Clear tblout
    DB::table('tblout')->truncate();

    return back()->with('success', 'Data moved to stock!');
}

}
