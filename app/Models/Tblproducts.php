<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tblproducts extends Model
{
    protected $table = 'tblproducts';

    protected $fillable = [
        'brand',
        'description',
        'sku',
        'upc',
        'slife',
        'trayod',
        'upt',
        'status',
        'bb_date',
    ];
}
