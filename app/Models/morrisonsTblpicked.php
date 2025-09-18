<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class morrisonsTblpicked extends Model
{
    protected $table = 'morrisons_tblpicked';
    protected $primaryKey = 'id'; // Assuming 'id' is the primary key
    public $timestamps = false; // If your table doesn't have created_at and updated_at columns
    protected $fillable = ['username', 'barcode', 'duedate', 'un', 'depo', 'quantity', 'bbdate', 'ponumber']; // Replace with your actual column names
}
