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

    public function web(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get($request->s);
        $json = json_decode($response->getBody()->getContents());
        return response()->json(['json' => $json]);
    }

    public function consultData($nickname, $platform)
    {
        switch ($platform) {
            case 'str':
                $url = 'https://stripchat.com/api/front/v2/models/username/' . $nickname . '/cam';
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
                    $url_stream = 'https://b-' . $hls . '.strpst.com/hls/' . $streamName . '/' . $streamName . '.m3u8';
                } catch (\Throwable $th) {
                    DB::table('api_log')->where('nickname', $nickname)->where('platform', $platform)->update(['online' => false]);
                    return response()->json();
                }
                break;
            case 'cht':
                $url = 'https://chaturbate.com/api/chatvideocontext/' . $nickname;
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
        ], [
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

    public function addMod($nickname, $platform)
    {
        DB::table('api_log')->insert(['nickname' => $nickname, 'platform' => $platform]);
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

    public function publicModUpdate()
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

    public function viewListPublicMod()
    {
        $this->publicModUpdate();
        $result = DB::table('list_mods')->get();
        return view('list_public', compact('result'));
    }

    public function viewPublicMod($id)
    {
        $mod = DB::table('list_mods')->where('user_id', $id)->first();
        return view('view_public', compact('mod'));
    }

    public function searchMod(Request $request, $limit_request)
    {
        $limit = $limit_request;
        $offset = 0;
        $primaryTag = 'girls';
        $filterGroupTags = '%5B%5B"tagLanguageColombian"%5D%5D';
        $sortBy = 'stripRanking';
        $parentTag = 'tagLanguageColombian';
        $userRole = 'user';
        $url = 'https://es.stripchat.com/api/front/models?';

        if ($limit) {
            $url .= 'limit='.$limit.'&';
        }
        if ($offset) {
            $url .= 'offset='.$offset.'&';
        }
        if ($primaryTag) {
            $url .= 'primaryTag='.$primaryTag.'&';
        }
        if ($filterGroupTags) {
            $url .= 'filterGroupTags='.$filterGroupTags.'&';
        }
        if ($sortBy) {
            $url .= 'sortBy='.$sortBy.'&';
        }
        if ($parentTag) {
            $url .= 'parentTag='.$parentTag.'&';
        }
        if ($userRole) {
            $url .= 'userRole='.$userRole;
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);
        $json = json_decode($response->getBody()->getContents());
        $model_find = [];
        $create = 0;
        $update = 0;
        $date_now =  Carbon::now();

        foreach ($json->models as $key => $mod) {

            try {
                $id_mod = DB::table('mods')->insertGetId(
                    [
                        'snapshotUrl' => $mod->snapshotUrl,
                        'widgetPreviewUrl' => $mod->widgetPreviewUrl,
                        'privateRate' => $mod->privateRate,
                        'p2pRate' => $mod->p2pRate,
                        'isNonNude' => $mod->isNonNude,
                        'avatarUrl' => $mod->avatarUrl,
                        'isPornStar' => $mod->isPornStar,
                        'id_mod' => $mod->id,
                        'country' => $mod->country,
                        'doSpy' => $mod->doSpy,
                        'doPrivate' => $mod->doPrivate,
                        'gender' => $mod->gender,
                        'isHd' => $mod->isHd,
                        'isVr' => $mod->isVr,
                        'is2d' => $mod->is2d,
                        'isExternalApp' => $mod->isExternalApp,
                        'isMobile' => $mod->isMobile,
                        'isModel' => $mod->isModel,
                        'isNew' => $mod->isNew,
                        'isLive' => $mod->isLive,
                        'isOnline' => $mod->isOnline,
                        'previewUrl' => $mod->previewUrl,
                        'previewUrlThumbBig' => $mod->previewUrlThumbBig,
                        'previewUrlThumbSmall' => $mod->previewUrlThumbSmall,
                        'broadcastServer' => $mod->broadcastServer,
                        'broadcastGender' => $mod->broadcastGender,
                        'snapshotServer' => $mod->snapshotServer,
                        'status' => $mod->status,
                        'topBestPlace' => $mod->topBestPlace,
                        'username' => $mod->username,
                        'statusChangedAt' => $mod->statusChangedAt,
                        'spyRate' => $mod->spyRate,
                        'publicRecordingsRate' => $mod->publicRecordingsRate,
                        'genderGroup' => $mod->genderGroup,
                        'popularSnapshotTimestamp' => $mod->popularSnapshotTimestamp,
                        'hasGroupShowAnnouncement' => $mod->hasGroupShowAnnouncement,
                        'groupShowType' => $mod->groupShowType,
                        'hallOfFamePosition' => $mod->hallOfFamePosition,
                        'snapshotTimestamp' => $mod->snapshotTimestamp,
                        'hlsPlaylist' => $mod->hlsPlaylist,
                        'isAvatarApproved' => $mod->isAvatarApproved,
                        'isTagVerified' => $mod->isTagVerified,
                        'created_at' => $date_now,
                        'updated_at' => $date_now,
                    ]
                );
                $create ++;
            } catch (\Throwable $th) {
                $affected = DB::table('mods')
                    ->where('id_mod', $mod->id)
                    ->update(
                    [
                        'snapshotUrl' => $mod->snapshotUrl,
                        'widgetPreviewUrl' => $mod->widgetPreviewUrl,
                        'privateRate' => $mod->privateRate,
                        'p2pRate' => $mod->p2pRate,
                        'isNonNude' => $mod->isNonNude,
                        'avatarUrl' => $mod->avatarUrl,
                        'isPornStar' => $mod->isPornStar,
                        'id_mod' => $mod->id,
                        'country' => $mod->country,
                        'doSpy' => $mod->doSpy,
                        'doPrivate' => $mod->doPrivate,
                        'gender' => $mod->gender,
                        'isHd' => $mod->isHd,
                        'isVr' => $mod->isVr,
                        'is2d' => $mod->is2d,
                        'isExternalApp' => $mod->isExternalApp,
                        'isMobile' => $mod->isMobile,
                        'isModel' => $mod->isModel,
                        'isNew' => $mod->isNew,
                        'isLive' => $mod->isLive,
                        'isOnline' => $mod->isOnline,
                        'previewUrl' => $mod->previewUrl,
                        'previewUrlThumbBig' => $mod->previewUrlThumbBig,
                        'previewUrlThumbSmall' => $mod->previewUrlThumbSmall,
                        'broadcastServer' => $mod->broadcastServer,
                        'broadcastGender' => $mod->broadcastGender,
                        'snapshotServer' => $mod->snapshotServer,
                        'status' => $mod->status,
                        'topBestPlace' => $mod->topBestPlace,
                        'username' => $mod->username,
                        'statusChangedAt' => $mod->statusChangedAt,
                        'spyRate' => $mod->spyRate,
                        'publicRecordingsRate' => $mod->publicRecordingsRate,
                        'genderGroup' => $mod->genderGroup,
                        'popularSnapshotTimestamp' => $mod->popularSnapshotTimestamp,
                        'hasGroupShowAnnouncement' => $mod->hasGroupShowAnnouncement,
                        'groupShowType' => $mod->groupShowType,
                        'hallOfFamePosition' => $mod->hallOfFamePosition,
                        'snapshotTimestamp' => $mod->snapshotTimestamp,
                        'hlsPlaylist' => $mod->hlsPlaylist,
                        'isAvatarApproved' => $mod->isAvatarApproved,
                        'isTagVerified' => $mod->isTagVerified,
                        'updated_at' => $date_now,
                    ]
                );
                $update ++;
            }
        }

        $offline = DB::table('mods')
            ->where('snapshotServer', '')
            ->count();

        DB::table('mods')
            ->where('snapshotServer', '')
            ->update(['isOnline' => 0]);

        return response()->json(['Guardados: ' => $create, 'Actualizados: ' => $update, 'Offline: ' => $offline]);
    }

    public function listMods(Request $request)
    {
        $skip = 0;
        $take = 15;
        $per_page = ($request->per_page) ? $request->per_page : 20;
        $count = DB::table('mods')->count();
        $limit = intdiv($count, $per_page);
        $prev = ($request->page == 0 || empty($request->page)) ? false : $request->page - 1;
        $next = ($request->page == $limit) ? false : $request->page + 1;
        $next = ($next == 1) ? 2 : $next;
        if ($request->page) {
            $take = $per_page * $request->page;
            $skip = $take - $per_page;
        }

        $result = DB::table('mods')->skip($skip)->take($per_page)->orderByDesc('isOnline')->get();
        $url_next = ($next) ? $request->url() . '?page=' . $next : false;
        $url_prev = ($prev) ? $request->url() . '?page=' . $prev : false;

        return view('mods', compact('result', 'skip', 'take', 'per_page', 'url_next', 'url_prev', 'count'));
    }

    public function showMod($id)
    {
        $mod = DB::table('mods')->where('id_mod', $id)->first();
        $description = $this->updateDataMod($mod);

        $data_hls = collect($description->cam->viewServers);
        $hls = $data_hls->sortKeys()['flashphoner-hls'];
        $streamName = $description->cam->streamName;
        $preview = $description->user->user->previewUrl;
        $url_stream = 'https://b-' . $hls . '.strpst.com/hls/' . $streamName . '/' . $streamName . '.m3u8';
        // dd($description);
        return view('view_mod_full', compact('mod', 'description', 'url_stream'));
    }

    private function updateDataMod($mod) {
        $id_mod = $mod->id;
        $url_description = 'https://es.stripchat.com/api/front/v2/models/username/'.$mod->username.'/cam';
        $client_description = new \GuzzleHttp\Client();
        $response_description = $client_description->get($url_description);
        $json_description = json_decode($response_description->getBody()->getContents());

        try {
            DB::table('descriptions')->insert(
                [
                    'mod_id' => $id_mod,
                    'canAddFriends' => $json_description->user->canAddFriends,
                    'isInFavorites' => $json_description->user->isInFavorites,
                    'isPmSubscribed' => $json_description->user->isPmSubscribed,
                    'isSubscribed' => $json_description->user->isSubscribed,
                    'subscriptionModel' => $json_description->user->subscriptionModel,
                    'isProfileAvailable' => $json_description->user->isProfileAvailable,
                    'friendship' => $json_description->user->friendship,
                    'isBanned' => $json_description->user->isBanned,
                    'isMuted' => $json_description->user->isMuted,
                    'isStudioModerator' => $json_description->user->isStudioModerator,
                    'isStudioAdmin' => $json_description->user->isStudioAdmin,
                    'isBannedByKnight' => $json_description->user->isBannedByKnight,
                    'banExpiresAt' => $json_description->user->banExpiresAt,
                    'isGeoBanned' => $json_description->user->isGeoBanned,
                    'photosCount' => $json_description->user->photosCount,
                    'videosCount' => $json_description->user->videosCount,
                    'currPosition' => $json_description->user->currPosition,
                    'currPoints' => $json_description->user->currPoints,
                    'relatedModelsCount' => $json_description->user->relatedModelsCount,
                    'shouldShowOtherModels' => $json_description->user->shouldShowOtherModels,
                    'previewReviewStatus' => $json_description->user->previewReviewStatus,
                    'feedAvailable' => $json_description->user->feedAvailable,
                ]
            );

            DB::table('users')->insert(
                [
                    'mod_id' => $id_mod,
                    'id_mod' => $json_description->user->user->id,
                    'isDeleted' => $json_description->user->user->isDeleted,
                    'name' => $json_description->user->user->name,
                    'birthDate' => $json_description->user->user->birthDate,
                    'country' => $json_description->user->user->country,
                    'region' => $json_description->user->user->region,
                    'city' => $json_description->user->user->city,
                    'cityId' => $json_description->user->user->cityId,
                    'interestedIn' => $json_description->user->user->interestedIn,
                    'bodyType' => $json_description->user->user->bodyType,
                    'ethnicity' => $json_description->user->user->ethnicity,
                    'hairColor' => $json_description->user->user->hairColor,
                    'eyeColor' => $json_description->user->user->eyeColor,
                    'subculture' => $json_description->user->user->subculture,
                    'description' => $json_description->user->user->description,
                    'showProfileTo' => $json_description->user->user->showProfileTo,
                    'amazonWishlist' => $json_description->user->user->amazonWishlist,
                    'age' => $json_description->user->user->age,
                    'kingId' => $json_description->user->user->kingId,
                    'becomeKingThreshold' => $json_description->user->user->becomeKingThreshold,
                    'favoritedCount' => $json_description->user->user->favoritedCount,
                    'whoCanChat' => $json_description->user->user->whoCanChat,
                    'spyRate' => $json_description->user->user->spyRate,
                    'privateRate' => $json_description->user->user->privateRate,
                    'p2pRate' => $json_description->user->user->p2pRate,
                    'privateMinDuration' => $json_description->user->user->privateMinDuration,
                    'p2pMinDuration' => $json_description->user->user->p2pMinDuration,
                    'privateOfflineMinDuration' => $json_description->user->user->privateOfflineMinDuration,
                    'p2pOfflineMinDuration' => $json_description->user->user->p2pOfflineMinDuration,
                    'p2pVoiceRate' => $json_description->user->user->p2pVoiceRate,
                    'groupRate' => $json_description->user->user->groupRate,
                    'ticketRate' => $json_description->user->user->ticketRate,
                    'publicRecordingsRate' => $json_description->user->user->publicRecordingsRate,
                    'status' => $json_description->user->user->status,
                    'broadcastServer' => $json_description->user->user->broadcastServer,
                    'ratingPrivate' => $json_description->user->user->ratingPrivate,
                    'ratingPrivateUsers' => $json_description->user->user->ratingPrivateUsers,
                    'topBestPlace' => $json_description->user->user->topBestPlace,
                    'statusChangedAt' => $json_description->user->user->statusChangedAt,
                    'wentIdleAt' => $json_description->user->user->wentIdleAt,
                    'broadcastGender' => $json_description->user->user->broadcastGender,
                    'isHd' => $json_description->user->user->isHd,
                    'isHls240p' => $json_description->user->user->isHls240p,
                    'isVr' => $json_description->user->user->isVr,
                    'is2d' => $json_description->user->user->is2d,
                    'isMlNonNude' => $json_description->user->user->isMlNonNude,
                    'isDisableMlNonNude' => $json_description->user->user->isDisableMlNonNude,
                    'hasChatRestrictions' => $json_description->user->user->hasChatRestrictions,
                    'isExternalApp' => $json_description->user->user->isExternalApp,
                    'isStorePrivateRecordings' => $json_description->user->user->isStorePrivateRecordings,
                    'isStorePublicRecordings' => $json_description->user->user->isStorePublicRecordings,
                    'isMobile' => $json_description->user->user->isMobile,
                    'spyMinimum' => $json_description->user->user->spyMinimum,
                    'privateMinimum' => $json_description->user->user->privateMinimum,
                    'privateOfflineMinimum' => $json_description->user->user->privateOfflineMinimum,
                    'p2pMinimum' => $json_description->user->user->p2pMinimum,
                    'p2pOfflineMinimum' => $json_description->user->user->p2pOfflineMinimum,
                    'p2pVoiceMinimum' => $json_description->user->user->p2pVoiceMinimum,
                    'previewUrl' => $json_description->user->user->previewUrl,
                    'previewUrlThumbBig' => $json_description->user->user->previewUrlThumbBig,
                    'previewUrlThumbSmall' => $json_description->user->user->previewUrlThumbSmall,
                    'doPrivate' => $json_description->user->user->doPrivate,
                    'doP2p' => $json_description->user->user->doP2p,
                    'doSpy' => $json_description->user->user->doSpy,
                    'snapshotServer' => $json_description->user->user->snapshotServer,
                    'ratingPosition' => $json_description->user->user->ratingPosition,
                    'isNew' => $json_description->user->user->isNew,
                    'isLive' => $json_description->user->user->isLive,
                    'hallOfFamePosition' => $json_description->user->user->hallOfFamePosition,
                    'isPornStar' => $json_description->user->user->isPornStar,
                    'broadcastCountry' => $json_description->user->user->broadcastCountry,
                    'username' => $json_description->user->user->username,
                    'login' => $json_description->user->user->login,
                    'domain' => $json_description->user->user->domain,
                    'gender' => $json_description->user->user->gender,
                    'genderDoc' => $json_description->user->user->genderDoc,
                    'showTokensTo' => $json_description->user->user->showTokensTo,
                    'offlineStatus' => $json_description->user->user->offlineStatus,
                    'offlineStatusUpdatedAt' => $json_description->user->user->offlineStatusUpdatedAt,
                    'isOnline' => $json_description->user->user->isOnline,
                    'isBlocked' => $json_description->user->user->isBlocked,
                    'avatarUrl' => $json_description->user->user->avatarUrl,
                    'avatarUrlThumb' => $json_description->user->user->avatarUrlThumb,
                    'isRegular' => $json_description->user->user->isRegular,
                    'isExGreen' => $json_description->user->user->isExGreen,
                    'isGold' => $json_description->user->user->isGold,
                    'isUltimate' => $json_description->user->user->isUltimate,
                    'isGreen' => $json_description->user->user->isGreen,
                    'hasVrDevice' => $json_description->user->user->hasVrDevice,
                    'isModel' => $json_description->user->user->isModel,
                    'isStudio' => $json_description->user->user->isStudio,
                    'isAdmin' => $json_description->user->user->isAdmin,
                    'isSupport' => $json_description->user->user->isSupport,
                    'isFinance' => $json_description->user->user->isFinance,
                    'isOfflinePrivateAvailable' => $json_description->user->user->isOfflinePrivateAvailable,
                    'isApprovedModel' => $json_description->user->user->isApprovedModel,
                    'isDisplayedModel' => $json_description->user->user->isDisplayedModel,
                    'hasAdminBadge' => $json_description->user->user->hasAdminBadge,
                    'isPromo' => $json_description->user->user->isPromo,
                    'isUnThrottled' => $json_description->user->user->isUnThrottled,
                    'userRanking' => $json_description->user->user->userRanking,
                    'snapshotTimestamp' => $json_description->user->user->snapshotTimestamp,
                    'contestGender' => $json_description->user->user->contestGender,
                ]
            );

        } catch (\Throwable $th) {
            DB::table('descriptions')
                ->where('mod_id', $id_mod)
                ->update(
                [
                    'mod_id' => $id_mod,
                    'canAddFriends' => $json_description->user->canAddFriends,
                    'isInFavorites' => $json_description->user->isInFavorites,
                    'isPmSubscribed' => $json_description->user->isPmSubscribed,
                    'isSubscribed' => $json_description->user->isSubscribed,
                    'subscriptionModel' => $json_description->user->subscriptionModel,
                    'isProfileAvailable' => $json_description->user->isProfileAvailable,
                    'friendship' => $json_description->user->friendship,
                    'isBanned' => $json_description->user->isBanned,
                    'isMuted' => $json_description->user->isMuted,
                    'isStudioModerator' => $json_description->user->isStudioModerator,
                    'isStudioAdmin' => $json_description->user->isStudioAdmin,
                    'isBannedByKnight' => $json_description->user->isBannedByKnight,
                    'banExpiresAt' => $json_description->user->banExpiresAt,
                    'isGeoBanned' => $json_description->user->isGeoBanned,
                    'photosCount' => $json_description->user->photosCount,
                    'videosCount' => $json_description->user->videosCount,
                    'currPosition' => $json_description->user->currPosition,
                    'currPoints' => $json_description->user->currPoints,
                    'relatedModelsCount' => $json_description->user->relatedModelsCount,
                    'shouldShowOtherModels' => $json_description->user->shouldShowOtherModels,
                    'previewReviewStatus' => $json_description->user->previewReviewStatus,
                    'feedAvailable' => $json_description->user->feedAvailable,
                ]
            );

            DB::table('users')
                ->where('mod_id', $id_mod)
                ->update(
                [
                    'mod_id' => $id_mod,
                    'id_mod' => $json_description->user->user->id,
                    'isDeleted' => $json_description->user->user->isDeleted,
                    'name' => $json_description->user->user->name,
                    'birthDate' => $json_description->user->user->birthDate,
                    'country' => $json_description->user->user->country,
                    'region' => $json_description->user->user->region,
                    'city' => $json_description->user->user->city,
                    'cityId' => $json_description->user->user->cityId,
                    'interestedIn' => $json_description->user->user->interestedIn,
                    'bodyType' => $json_description->user->user->bodyType,
                    'ethnicity' => $json_description->user->user->ethnicity,
                    'hairColor' => $json_description->user->user->hairColor,
                    'eyeColor' => $json_description->user->user->eyeColor,
                    'subculture' => $json_description->user->user->subculture,
                    'description' => $json_description->user->user->description,
                    'showProfileTo' => $json_description->user->user->showProfileTo,
                    'amazonWishlist' => $json_description->user->user->amazonWishlist,
                    'age' => $json_description->user->user->age,
                    'kingId' => $json_description->user->user->kingId,
                    'becomeKingThreshold' => $json_description->user->user->becomeKingThreshold,
                    'favoritedCount' => $json_description->user->user->favoritedCount,
                    'whoCanChat' => $json_description->user->user->whoCanChat,
                    'spyRate' => $json_description->user->user->spyRate,
                    'privateRate' => $json_description->user->user->privateRate,
                    'p2pRate' => $json_description->user->user->p2pRate,
                    'privateMinDuration' => $json_description->user->user->privateMinDuration,
                    'p2pMinDuration' => $json_description->user->user->p2pMinDuration,
                    'privateOfflineMinDuration' => $json_description->user->user->privateOfflineMinDuration,
                    'p2pOfflineMinDuration' => $json_description->user->user->p2pOfflineMinDuration,
                    'p2pVoiceRate' => $json_description->user->user->p2pVoiceRate,
                    'groupRate' => $json_description->user->user->groupRate,
                    'ticketRate' => $json_description->user->user->ticketRate,
                    'publicRecordingsRate' => $json_description->user->user->publicRecordingsRate,
                    'status' => $json_description->user->user->status,
                    'broadcastServer' => $json_description->user->user->broadcastServer,
                    'ratingPrivate' => $json_description->user->user->ratingPrivate,
                    'ratingPrivateUsers' => $json_description->user->user->ratingPrivateUsers,
                    'topBestPlace' => $json_description->user->user->topBestPlace,
                    'statusChangedAt' => $json_description->user->user->statusChangedAt,
                    'wentIdleAt' => $json_description->user->user->wentIdleAt,
                    'broadcastGender' => $json_description->user->user->broadcastGender,
                    'isHd' => $json_description->user->user->isHd,
                    'isHls240p' => $json_description->user->user->isHls240p,
                    'isVr' => $json_description->user->user->isVr,
                    'is2d' => $json_description->user->user->is2d,
                    'isMlNonNude' => $json_description->user->user->isMlNonNude,
                    'isDisableMlNonNude' => $json_description->user->user->isDisableMlNonNude,
                    'hasChatRestrictions' => $json_description->user->user->hasChatRestrictions,
                    'isExternalApp' => $json_description->user->user->isExternalApp,
                    'isStorePrivateRecordings' => $json_description->user->user->isStorePrivateRecordings,
                    'isStorePublicRecordings' => $json_description->user->user->isStorePublicRecordings,
                    'isMobile' => $json_description->user->user->isMobile,
                    'spyMinimum' => $json_description->user->user->spyMinimum,
                    'privateMinimum' => $json_description->user->user->privateMinimum,
                    'privateOfflineMinimum' => $json_description->user->user->privateOfflineMinimum,
                    'p2pMinimum' => $json_description->user->user->p2pMinimum,
                    'p2pOfflineMinimum' => $json_description->user->user->p2pOfflineMinimum,
                    'p2pVoiceMinimum' => $json_description->user->user->p2pVoiceMinimum,
                    'previewUrl' => $json_description->user->user->previewUrl,
                    'previewUrlThumbBig' => $json_description->user->user->previewUrlThumbBig,
                    'previewUrlThumbSmall' => $json_description->user->user->previewUrlThumbSmall,
                    'doPrivate' => $json_description->user->user->doPrivate,
                    'doP2p' => $json_description->user->user->doP2p,
                    'doSpy' => $json_description->user->user->doSpy,
                    'snapshotServer' => $json_description->user->user->snapshotServer,
                    'ratingPosition' => $json_description->user->user->ratingPosition,
                    'isNew' => $json_description->user->user->isNew,
                    'isLive' => $json_description->user->user->isLive,
                    'hallOfFamePosition' => $json_description->user->user->hallOfFamePosition,
                    'isPornStar' => $json_description->user->user->isPornStar,
                    'broadcastCountry' => $json_description->user->user->broadcastCountry,
                    'username' => $json_description->user->user->username,
                    'login' => $json_description->user->user->login,
                    'domain' => $json_description->user->user->domain,
                    'gender' => $json_description->user->user->gender,
                    'genderDoc' => $json_description->user->user->genderDoc,
                    'showTokensTo' => $json_description->user->user->showTokensTo,
                    'offlineStatus' => $json_description->user->user->offlineStatus,
                    'offlineStatusUpdatedAt' => $json_description->user->user->offlineStatusUpdatedAt,
                    'isOnline' => $json_description->user->user->isOnline,
                    'isBlocked' => $json_description->user->user->isBlocked,
                    'avatarUrl' => $json_description->user->user->avatarUrl,
                    'avatarUrlThumb' => $json_description->user->user->avatarUrlThumb,
                    'isRegular' => $json_description->user->user->isRegular,
                    'isExGreen' => $json_description->user->user->isExGreen,
                    'isGold' => $json_description->user->user->isGold,
                    'isUltimate' => $json_description->user->user->isUltimate,
                    'isGreen' => $json_description->user->user->isGreen,
                    'hasVrDevice' => $json_description->user->user->hasVrDevice,
                    'isModel' => $json_description->user->user->isModel,
                    'isStudio' => $json_description->user->user->isStudio,
                    'isAdmin' => $json_description->user->user->isAdmin,
                    'isSupport' => $json_description->user->user->isSupport,
                    'isFinance' => $json_description->user->user->isFinance,
                    'isOfflinePrivateAvailable' => $json_description->user->user->isOfflinePrivateAvailable,
                    'isApprovedModel' => $json_description->user->user->isApprovedModel,
                    'isDisplayedModel' => $json_description->user->user->isDisplayedModel,
                    'hasAdminBadge' => $json_description->user->user->hasAdminBadge,
                    'isPromo' => $json_description->user->user->isPromo,
                    'isUnThrottled' => $json_description->user->user->isUnThrottled,
                    'userRanking' => $json_description->user->user->userRanking,
                    'snapshotTimestamp' => $json_description->user->user->snapshotTimestamp,
                    'contestGender' => $json_description->user->user->contestGender,
                ]
            );
        }

        return $json_description;
    }
}
