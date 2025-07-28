<?php
Namespace App\Admin\Extensions;

Use OpenAdmin\Admin\Grid\Exporters\ExcelExporter;

class TblproductExporter extends ExcelExporter
{
    protected $fileName = 'Products list.xlsx';

    protected $columns = [
        'id' => 'ID',
        'description' => 'Product',
        'sku' => 'SKU',
        'upc' => 'UPC',
        'slife' => 'Shelf Life',
        'trayod' => 'Basket on Dollies',
        'upt' => 'UPT',
        'status' => 'Status',
    ];
}
