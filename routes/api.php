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

$router->group([
    'middleware' => 'api'
], function (Router $router) {
    $router->get('printers', 'ApiController@listPrinters');

    $router->get('printers/{printer}', 'ApiController@getPrinter');

    $router->get('status', 'ApiController@status');
});
