<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\TblinController;
use App\Admin\Controllers\TblstockinController;
use App\Admin\Controllers\TbloutController;
use App\Admin\Controllers\TblstockoutController;
use App\Admin\Controllers\TblproductsController;
use App\Admin\Controllers\TblorderController;
use App\Admin\Controllers\TbldestinationsController;
use OpenAdmin\Admin\Facades\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    //STOCK START
    $router->resource('tblin', TblinController::class);
    $router->resource('tblstockin', TblstockinController::class);
    $router->resource('tblout', TbloutController::class);
    $router->resource('tblstockout', TblstockoutController::class);
    //STOCK END

    //inventory
    $router->resource('tblproducts', TblproductsController::class);
    $router->resource('tblorders', TblorderController::class);
    $router->resource('tbldestinations', TbldestinationsController::class);
    //inventory end

});
