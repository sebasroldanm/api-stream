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

$router->get('/v1/consult/{nickname}/{platform}', function ($nickname, $platform) use ($router) {
    switch ($platform) {
        case 'str':
            $url = 'https://stripchat.com/api/front/v2/models/username/'.$nickname.'/cam';
            $platform = 'STR';
            try {
                $client = new \GuzzleHttp\Client();
                $response = $client->get($url);
                $json = json_decode($response->getBody()->getContents());
                if (empty($json->cam)) {
                    return response()->json();
                }
            } catch (\Throwable $th) {
                return response()->json();
            }
            break;
        case 'cht':
            $url = 'https://chaturbate.com/api/chatvideocontext/'.$nickname;
            $platform = 'CHT';
            try {
                $client = new \GuzzleHttp\Client();
                $response = $client->get($url);
                $json = json_decode($response->getBody()->getContents());
                if (!isset($json->hls_source) || empty($json->hls_source)) {
                    return response()->json();
                }
            } catch (\Throwable $th) {
                return response()->json();
            }
            break;
        default:
            return response()->json();
            break;
    }
    // // DB::table('api_log')->insert([
    // //     'nickname'  => $nickname,
    // //     'plataform' => $platform,
    // //     'origin'    => 'Consult',
    // //     'created_at' => Carbon::now(),
    // //     'updated_at' => Carbon::now(),
    // // ]);
    return response()->json($json);
});
