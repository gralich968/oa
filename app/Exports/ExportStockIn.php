<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Tblstockin;

class ExportStockIn implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tblstockin::select('*')->get();
    }
    public function map($Tblstockin):array
    {
		return[
		$Tblstockin->id,
		$Tblstockin->LCode,
		$Tblstockin->sku,
		$Tblstockin->qty,
		$Tblstockin->created_at,
		];
	}
	
    
    public function headings():array
    {
		return[
		'#',
		'L Code',
		'P Code',
		'C Pallet',
		'Created at'
		];
	}
}
