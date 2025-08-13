<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tblpickings extends Model
{
    protected $table = 'tblpickings';
    protected $fillable = ['position', 'product', 'quantity', 'picked', 'sku'];
}
