<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mstblpicked extends Model
{
   protected $table = 'mstblpicked';
   protected $primaryKey = 'id'; // Assuming 'id' is the primary key
   public $timestamps = false; // If your table doesn't have created_at and updated_at columns
   protected $fillable = ['username', 'barcode', 'duedate', 'un', 'depo']; // Replace with your actual column names
}
