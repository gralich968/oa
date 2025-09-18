<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Mstblpicked;
use App\Models\Morrisonstblpicked;
use App\Models\Morrisonstblorders;
use App\Models\Tbldestinations;
use Illuminate\Support\Facades\DB;
=======
>>>>>>> 74e963bcc8f2bf9698d0bed58c9d3b0b21d67cbe

class MsOrderController extends Controller
{
    public function scaninForm() {
<<<<<<< HEAD
    $mstblpicked = Mstblpicked::all();
    $depos = Tbldestinations::all()->where('is_active', 1);
    return view('ms.pick', compact('mstblpicked', 'depos'));
}


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

    }


    //MORRISONS ORDER
    public function scanMorrisonsForm() {
        $MorrisonsTblpicked = MorrisonsTblpicked::all();
        $depos = Tbldestinations::all()->where('is_active', 1)->where('brand', 'Morrisons');
        return view('morrisons.pick', compact('MorrisonsTblpicked', 'depos'));
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

  // Generate one unique number for this batch
    $batchNumber = uniqid('batch_');

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

=======
    $tblin = Tblpick::all();
    return view('ms.pick', compact('tblpick));
}
>>>>>>> 74e963bcc8f2bf9698d0bed58c9d3b0b21d67cbe
}
