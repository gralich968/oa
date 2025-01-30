<?php

use Illuminate\Routing\Router;

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

});
