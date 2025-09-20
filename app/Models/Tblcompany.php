<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tblcompany extends Model
{
    protected $table = 'tblcompany';
    protected $primaryKey = 'id';
    protected $fillable = [
        'company',
        'company_pref',
        'company_sscc',
        'company_code',
        'company_name',
        'company_address',
        'company_gln',
        'company_timezone',
        'company_lang',
    ];
}
