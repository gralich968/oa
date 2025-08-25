<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ImportOrderController extends Controller
{
    public function index()
    {
        $data = DB::table('tblorder')->orderBy('id', 'ASC')->get();
        return view('import_order', compact('data'));
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


 // Fill empty cells with previous row's data
    if ($previousRow !== null) {
        foreach ($rowArray as $index => $cell) {
            if (empty($cell)) {
                // If it's requestQty column (index 9), set to 0
                if ($index === 9) {
                    $rowArray[$index] = 0;
                } elseif (isset($previousRow[$index])) {
                    $rowArray[$index] = $previousRow[$index];
                }
            }
        }
    } else {
        // Handle first row: if requestQty is empty, set to 0
        if (empty($rowArray[9])) {
            $rowArray[9] = 0;
        }
    }

$previousRow = $rowArray;


            if (count($rowArray) >= 12) {

                $insert_data[] = [
                    'companyCode'           => $rowArray[0],
                    'orderNumber'           => $rowArray[1],
                    'orderDate'             => $this->formatExcelDate($rowArray[2]),
                    'partnerRef'            => $rowArray[3],
                    'dueDate'               => $this->formatExcelDate($rowArray[4]),
                    'orderType'             => $rowArray[5],
                    'positionsposId'        => $rowArray[6],
                    'positioncompanyCode'   => $rowArray[7],
                    'itemNumber'            => $rowArray[8],
                    'requestQty'            => $rowArray[9],
                    'positionuom'           => $rowArray[10],
                    'sparenumber1'          => $rowArray[11],
                ];
            }
        }

        if (!empty($insert_data)) {
            DB::table('tblorder')->insert($insert_data);
        }

        return back()->with('success', 'Excel data imported successfully.');
    }

private function formatExcelDate($value)
{
    try {
        // Handle Excel numeric date format
        if (is_numeric($value)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
        }

        // Handle string date format
        return Carbon::parse($value)->format('Y-m-d');
    } catch (\Exception $e) {
        return null; // or return today's date, or log the error
    }
}

}
