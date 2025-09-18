<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tblin extends Model
{
    protected $table = 'tblin';
    protected $fillable = ['barcode', 'username', 'un'];
}
