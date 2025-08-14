<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportPickingsController extends Controller
{
    public function index()
    {
        $data = DB::table('tblpickings')->orderBy('id', 'ASC')->get();
        return view('import_pickings', compact('data'));
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'select_file' => 'required|file|mimes:xls,xlsx,ods'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $collection = Excel::toCollection(null, $request->file('select_file'));

        if ($collection->isEmpty()) {
            return back()->with('error', 'Excel file is empty.');
        }

        $rows = $collection->first();
        $insert_data = [];
        $previousRow = null;

        foreach ($rows->Skip(1) as $row) {
            $rowArray = $row->toArray();

         // Only insert rows where quantity > 0 and row is not completely null
          if ($rowArray[2] !== null && (float)$rowArray[2] > 0) {
                $insert_data[] = [
                    'position' => $rowArray[0],
                    'product'  => $rowArray[1],
                    'quantity' => $rowArray[2],
                    'picked'   => $rowArray[3],
                    'sku'      => substr($rowArray[1], 0, 8),
                ];
            }
        }

        if (!empty($insert_data)) {
            DB::table('tblpickings')->insert($insert_data);

            // Group by product and calculate sums
            $results = [];
            foreach ($insert_data as $row) {
                $product = $row['product'];
                if (!isset($results[$product])) {
                    $results[$product] = [
                        'product' => $product,
                        'quantity_sum' => 0,
                        'picked_sum' => 0,
                    ];
                }
                $results[$product]['quantity_sum'] += (float)$row['quantity'];
                $results[$product]['picked_sum'] += (float)$row['picked'];
            }

            $insert_results = [];
            foreach ($results as $product => $data) {
                // Fetch trayod from tblproduct where sku matches
                $trayod = DB::table('tblproducts')
                    ->where('sku', substr($product, 0, 8))
                    ->value('trayod');

                $insert_results[] = [
                    'product' => $product,
                    'quantity_sum' => $data['quantity_sum'],
                    'picked_sum' => $data['picked_sum'],
                    'remaining' => $data['quantity_sum'] - $data['picked_sum'],
                    'sku' => substr($product, 0, 8),
                    'trayod' => ceil($data['quantity_sum'] / $trayod),
                ];
            }

            DB::table('tblpickingsresult')->insert($insert_results);
        }

        return back()->with('success', 'Excel data imported successfully.');
    }

}

