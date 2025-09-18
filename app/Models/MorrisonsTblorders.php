<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MorrisonsTblorders extends Model
{
   protected $table = 'morrisons_tblorders';
  protected $fillable = [
           'companyCode',
           'orderNumber',
           'orderDate',
           'partenerRef',
           'dueDate',
           'orderType',
           'positionsposId',
           'positioncompanyCode',
           'itemNumber',
           'requestQty',
           'positionuom',
           'sparenuber1',
    ];
    public function product()
{
    return $this->hasOne(Tblproducts::class, 'sku', 'itemNumber');
}
}
