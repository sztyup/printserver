<?php

use Illuminate\Routing\Router;

/** @var Router $router */
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['middleware' => 'api', 'prefix' => 'printers', 'as' => 'printers'], function (Router $router) {
    $router->get('/', 'ApiController@listPrinters');
    $router->get('/{printer}/status', 'ApiController@getPrinter');

    $router->post('/{printer}/print', 'ApiController@printWith');
});
