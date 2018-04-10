<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->get('/', 'WebController@index')->name('index');