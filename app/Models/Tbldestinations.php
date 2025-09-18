<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tbldestinations extends Model
{
    use SoftDeletes;
    protected $table = 'tbldestinations';
    protected $fillable = [
        'brand',
        'depo_name',
        'depo_code',
        'depo_type',
        'depo_gln',
    ];
}
