<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class ImportDestinationsController extends Controller
{
    public function index()
    {
        $data = DB::table('tbldestinations')->orderBy('id', 'ASC')->get();
        return view('import_destinations', compact('data'));
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

            // Fill completely empty rows with data above
            if ($previousRow !== null) {
    foreach ($rowArray as $index => $cell) {
        if (empty($cell) && isset($previousRow[$index])) {
            $rowArray[$index] = $previousRow[$index];
        }
    }
}
$previousRow = $rowArray;

                $insert_data[] = [
                    'depo_code'           => $rowArray[0],
                    'depo_name'           => $rowArray[1],
                    'depo_type'           => $rowArray[2],
                    'depo_gln'            => $rowArray[3],

                ];
            }

            DB::table('tbldestinations')->insert($insert_data);

        return back()->with('success', 'Excel data imported successfully.');
    }

}
