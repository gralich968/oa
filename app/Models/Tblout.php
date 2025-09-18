<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tblout extends Model
{
    protected $table = 'tblout';
    protected $fillable = ['barcode', 'username', 'un'];
}
