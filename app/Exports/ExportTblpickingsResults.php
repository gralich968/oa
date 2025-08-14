<?php

namespace App\Exports;

use App\Models\TblpickingsResults;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportTblpickingsResults implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TblpickingsResults::select('*')->get();
    }

    public function map($TblpickingsResults):array
    {
		return[
		$TblpickingsResults->id,
		$TblpickingsResults->product,
		$TblpickingsResults->quantity_sum,
		$TblpickingsResults->picked_sum,
		$TblpickingsResults->remaining,
        $TblpickingsResults->sku,
        $TblpickingsResults->trayod,
		];
	}

public function headings():array
    {
		return[
		'#',
		'Product',
		'Quantity',
		'Picked',
		'Remaining',
		'SKU',
		'Dollies'
		];
	}

}
