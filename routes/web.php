<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return view('greeting', ['info' => $router->app->version()]);
});

$router->get('/list', 'StreamServerController@list');
$router->get('/clear', 'StreamServerController@clearData');
$router->get('/view/{nickname}', 'StreamServerController@viewMod');

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('consult/{nickname}/{platform}', 'StreamServerController@consultData');
    $router->get('consult/last/{nickname}/{platform}', 'StreamServerController@lastUrlStream');
});
