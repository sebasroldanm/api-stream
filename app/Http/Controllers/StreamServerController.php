<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StreamServerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function consultData(Request $request, $nickname, $platform)
    {
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
                    $data_hls = collect($json->cam->viewServers);
                    $hls = $data_hls->sortKeys()['flashphoner-hls'];
                    $streamName = $json->cam->streamName;
                    if (empty($streamName)) {
                        return response()->json();
                    }
                    $url_stream = 'https://b-'.$hls.'.strpst.com/hls/'.$streamName.'/'.$streamName.'.m3u8';
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
                    $url_stream = $json->hls_source;
                } catch (\Throwable $th) {
                    return response()->json();
                }
                break;
            default:
                return response()->json();
                break;
        }
        DB::table('api_log')->updateOrInsert([
            'nickname'  => $nickname,
            'platform' => $platform,
            'stream'    => $url_stream
        ],[
            'nickname'  => $nickname,
            'platform' => $platform,
            'stream'    => $url_stream,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json($json);
    }
}
