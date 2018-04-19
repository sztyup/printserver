<?php

/** @var Router $router */
use Illuminate\Routing\Router;

$router->get('/', 'WebController@index')->name('index');
$router->post('/', 'WebController@index')->name('index');
