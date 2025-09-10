<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MsOrderController extends Controller
{
    public function scaninForm() {
    $tblin = Tblpick::all();
    return view('ms.pick', compact('tblpick));
}
}
