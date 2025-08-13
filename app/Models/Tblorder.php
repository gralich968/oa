<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tblorder extends Model
{
    use SoftDeletes;

    protected $table = 'tblorder';
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


