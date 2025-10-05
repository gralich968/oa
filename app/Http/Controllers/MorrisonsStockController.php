<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MorrisonsStock;

class MorrisonsStockController extends Controller
{
public function stockIndex(Request $request)
{
    if ($request->isMethod('post')) {
        // Validate and save stock data
        $validated = $request->validate([
            'barcode' => 'required|string|max:255',
            'qty'     => 'required|string|min:1',
            'bbdate'  => 'required|date',
            // Add other fields as needed
        ]);

        MorrisonsStock::create($validated);

        // Optional: redirect after POST to avoid resubmission on refresh
        return redirect()->route('morrisons.stock')
                         ->with('success', 'Stock added successfully.');
    }

    // Retrieve all stock data
    $stocks = MorrisonsStock::all();

    // Pass data to the view
    return view('morrisons.stock', compact('stocks'));
}

}