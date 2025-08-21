<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Widgets\Box as WidgetsBox;
use App\Models\Tblorder; // Adjust the namespace according to your application structure
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Widgets\Box;


class HomeController extends Controller
{

    public function index(Content $content)
    {



// Get the first due date (you can change this to latest or earliest)
$dueDate = Tblorder::value('dueDate'); // Gets the first dueDate
$formattedDate = Carbon::parse($dueDate)->format('d-m-Y');
// Sum quantity_sum where trayod = 36 and 18, joining tblproducts on sku
            $sum36 = DB::table('tblpickingsresult')
            ->join('tblproducts', 'tblpickingsresult.sku', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 36)
            ->sum('tblpickingsresult.quantity_sum');

            $sum18 = DB::table('tblpickingsresult')
            ->join('tblproducts', 'tblpickingsresult.sku', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 18)
            ->sum('tblpickingsresult.quantity_sum');

             $sum360 = DB::table('tblpickingsresult')
            ->join('tblproducts', 'tblpickingsresult.sku', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 36)
            ->sum('tblpickingsresult.trayod');

            $sum180 = DB::table('tblpickingsresult')
            ->join('tblproducts', 'tblpickingsresult.sku', '=', 'tblproducts.sku')
            ->where('tblproducts.trayod', 18)
            ->sum('tblpickingsresult.trayod');

            $sumRemaining = DB::table('tblpickingsresult')
            ->sum('tblpickingsresult.remaining');

            $sumAll = $sum360 + $sum180;

            $tracks = ceil($sumAll / 64);

// Create the info box
$infoBox = new WidgetsBox('M&S Order Information', "<h5><p>Last due date: <strong>{$formattedDate}</strong> | Total Half Trays (trayod=36): <strong>{$sum36}</strong> | Total Metrics Trays (trayod=18): <strong>{$sum18}</strong> | Total Dollies: <strong>{$sumAll}</strong> | Total Tracks: <strong>{$tracks}</strong></p></h5>");
$infoBox->style('info')->solid();


$box = new Box('M&S Order Information', 'Box content', 'Box footer');
$box->header("M&S Order Information");
$box->content("<h5><p>Last due date: <strong>{$formattedDate}</strong> | Total Half Trays (trayod=36): <strong>{$sum36}</strong> | Total Metrics Trays (trayod=18): <strong>{$sum18}</strong> | The Remaining: <strong>{$sumRemaining}</strong></p></h5>");
$box->footer("<h5><p>Total Dollies: <strong>{$sumAll}</strong> | Total Tracks: <strong>{$tracks}</strong></p></h5>");
//box options
//$box->removable();
$box->collapsable();
$box->styles(["border"=>"1px solid #FFAA00","margin-top"=>"20px"]);
$box->render();


        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            //->title('Dashboard')
            //->description('Inventory Management System')
            ->row(Dashboard::title())
            //->row($infoBox)
            ->row($box);
    }
}
