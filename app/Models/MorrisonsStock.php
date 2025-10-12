<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MorrisonsStock extends Model
{
    protected $table = 'morrisons_stocks';
    protected $fillable = [
        'barcode', 
        'bbdate', 
        'qty',
    ];
}

