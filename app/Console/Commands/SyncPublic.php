<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronización listado público';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mods = DB::table('list_mods')->get();

        foreach ($mods as $key => $mod) {
            switch ($mod->platform) {
                case 'str':
                    $url = 'https://stripchat.com/api/front/v2/models/username/' . $mod->nickname . '/cam';
                    try {
                        $client = new \GuzzleHttp\Client();
                        $response = $client->get($url);
                        $json = json_decode($response->getBody()->getContents());
                        $data_save = [];
                        if (empty($json->cam)) {
                            $data_save = ['state' => false];
                        } else {
                            $streamName = $json->cam->streamName;
                            if (empty($streamName)) {
                                $data_save = ['state' => false];
                            } else {
                                $data_hls = collect($json->cam->viewServers);
                                $hls = $data_hls->sortKeys()['flashphoner-hls'];
                                $url_stream = 'https://b-' . $hls . '.strpst.com/hls/' . $streamName . '/' . $streamName . '.m3u8';
                                //datauser
                                $data_save = [
                                    'user_id' => $json->user->user->id,
                                    'description' => $json->user->user->description,
                                    'state' => true,
                                    'stream' => $url_stream,
                                    'isMobile' => $json->user->user->isMobile,
                                    'broadcastGender' => $json->user->user->broadcastGender,
                                    'previewUrl' => $json->user->user->previewUrl,
                                    'previewUrlThumbBig' => $json->user->user->previewUrlThumbBig,
                                    'previewUrlThumbSmall' => $json->user->user->previewUrlThumbSmall,
                                    'avatarUrl' => $json->user->user->avatarUrl,
                                    'avatarUrlThumb' => $json->user->user->avatarUrlThumb,
                                    'offlineStatusUpdatedAt' => Carbon::parse($json->user->user->offlineStatusUpdatedAt),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
                        }
                        DB::table('list_mods')->where('nickname', $mod->nickname)->update($data_save);
                    } catch (\Throwable $th) {
                        dd($th);
                    }
                    break;
                case 'cht':
                    $url = 'https://chaturbate.com/api/chatvideocontext/' . $mod->nickname;

                    break;
            }
        }
    }
}
