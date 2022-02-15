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

    public function consultData($nickname, $platform)
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
                        DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
                        return response()->json();
                    }
                    $data_hls = collect($json->cam->viewServers);
                    $hls = $data_hls->sortKeys()['flashphoner-hls'];
                    $streamName = $json->cam->streamName;
                    if (empty($streamName)) {
                        DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
                        return response()->json();
                    }
                    $url_stream = 'https://b-'.$hls.'.strpst.com/hls/'.$streamName.'/'.$streamName.'.m3u8';
                } catch (\Throwable $th) {
                    DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
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
                        DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
                        return response()->json();
                    }
                    $url_stream = $json->hls_source;
                } catch (\Throwable $th) {
                    DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
                    return response()->json();
                }
                break;
            default:
                DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
                return response()->json();
                break;
        }
        DB::table('api_log')->updateOrInsert([
            'nickname'  => $nickname,
            'platform'  => $platform,
            'stream'    => $url_stream
        ],[
            'nickname'  => $nickname,
            'platform'  => $platform,
            'stream'    => $url_stream,
            'online'    => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json($json);
    }

    public function lastUrlStream($nickname, $platform)
    {
        $result = DB::table('api_log')
            ->where('nickname', $nickname)
            ->where('platform', $platform)
            ->orderByDesc('id')
            ->first();
        return $result->stream;
    }

    public function list()
    {
        $this->clearData();
        $result = DB::table('api_log')->orderByDesc('updated_at')->get();
        return view('list', compact('result'));
    }

    public function clearData()
    {
        $modsRepeat = DB::table('api_log')->select('nickname')->groupBy('nickname')->havingRaw('COUNT(*)>1')->get();
        foreach ($modsRepeat as $key => $mod) {
            $del = DB::table('api_log')->where('nickname', $mod->nickname)->orderByDesc('id')->skip(1)->take(20)->get();
            foreach ($del as $destroy) {
                DB::table('api_log')->where('id', $destroy->id)->delete();
            }
        }
    }

    public function viewMod($nickname)
    {
        $mod = DB::table('api_log')->where('nickname', $nickname)->first();
        return view('view_mod', compact('mod'));
    }
}
