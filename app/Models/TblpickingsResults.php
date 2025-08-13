<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblpickingsResults extends Model
{
    protected $table = 'tblpickingsresult';
    protected $fillable = [
        'product',
        'quantity_sum',
        'picked_sum',
        'remaining',
        'sku',
        'trayod',
    ];
}
