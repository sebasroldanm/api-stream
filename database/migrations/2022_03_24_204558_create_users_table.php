<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mod_id')->unique();//{#177 ▶}
            $table->foreign('mod_id')->references('id')->on('mods');
            $table->string('id_mod')->nullable();// 47565663
            $table->boolean('isDeleted')->nullable();// false
            $table->string('name')->nullable()->nullable();// ""
            $table->string('birthDate')->nullable();// "1996-01-07"
            $table->string('country')->nullable();// "ca"
            $table->string('region')->nullable();// ""
            $table->string('city')->nullable();// ""
            $table->integer('cityId')->nullable();// 0

            $table->string('interestedIn')->nullable();// "interestedInEverybody"
            $table->string('bodyType')->nullable();// "bodyTypeThin"

            $table->string('ethnicity')->nullable();// "ethnicityWhite"
            $table->string('hairColor')->nullable();// "hairColorOther"
            $table->string('eyeColor')->nullable();// "eyeColorGreen"
            $table->string('subculture')->nullable();// "subcultureGlamour"
            $table->text('description')->nullable();// "I'm a 24 year old horny Canadian girl looking to flirt and have fun."
            $table->string('showProfileTo')->nullable();// "all"
            $table->text('amazonWishlist')->nullable();// "https://www.amazon.ca/hz/wishlist/ls/3VKVU0JDUVDDG?ref_=wl_share"
            $table->integer('age')->nullable();// 26

            $table->integer('kingId')->nullable();// 65859925
            $table->integer('becomeKingThreshold')->nullable();// 500
            $table->integer('favoritedCount')->nullable();// 146385
            $table->string('whoCanChat')->nullable();// "paying"
            $table->integer('spyRate')->nullable();// 32
            $table->integer('privateRate')->nullable();// 90
            $table->integer('p2pRate')->nullable();// 120
            $table->integer('privateMinDuration')->nullable();// 10
            $table->integer('p2pMinDuration')->nullable();// 8
            $table->integer('privateOfflineMinDuration')->nullable();// 10
            $table->integer('p2pOfflineMinDuration')->nullable();// 8
            $table->integer('p2pVoiceRate')->nullable();// 150
            $table->integer('groupRate')->nullable();// 32
            $table->integer('ticketRate')->nullable();// 16
            $table->integer('publicRecordingsRate')->nullable();// 44
            $table->string('status')->nullable();// "public"
            $table->string('broadcastServer')->nullable();// "us12"
            $table->float('ratingPrivate')->nullable();// 4.97059
            $table->integer('ratingPrivateUsers')->nullable();// 34
            $table->integer('topBestPlace')->nullable();// 2

            $table->string('statusChangedAt')->nullable();// "2022-03-24T23:55:35Z"
            $table->string('wentIdleAt')->nullable();// "2022-03-23T03:45:33Z"



            $table->string('broadcastGender')->nullable();// "female"
            $table->boolean('isHd')->nullable();// true
            $table->boolean('isHls240p')->nullable();// false
            $table->boolean('isVr')->nullable();// false
            $table->boolean('is2d')->nullable();// true
            $table->boolean('isMlNonNude')->nullable();// false
            $table->boolean('isDisableMlNonNude')->nullable();// false
            $table->boolean('hasChatRestrictions')->nullable();// false
            $table->boolean('isExternalApp')->nullable();// true
            $table->boolean('isStorePrivateRecordings')->nullable();// true
            $table->boolean('isStorePublicRecordings')->nullable();// true
            $table->boolean('isMobile')->nullable();// false
            $table->integer('spyMinimum')->nullable();// 32
            $table->integer('privateMinimum')->nullable();// 900
            $table->integer('privateOfflineMinimum')->nullable();// 900
            $table->integer('p2pMinimum')->nullable();// 960
            $table->integer('p2pOfflineMinimum')->nullable();// 960
            $table->integer('p2pVoiceMinimum')->nullable();// 450
            $table->text('previewUrl')->nullable();// "https://cdn.strpst.com/cdn/previews/a/6/b/a6b639d8bbea13f10b9e3765239e6257-full"
            $table->text('previewUrlThumbBig')->nullable();// "https://cdn.strpst.com/cdn/previews/a/6/b/a6b639d8bbea13f10b9e3765239e6257-thumb-big"
            $table->text('previewUrlThumbSmall')->nullable();// "https://cdn.strpst.com/cdn/previews/a/6/b/a6b639d8bbea13f10b9e3765239e6257-thumb-small"
            $table->boolean('doPrivate')->nullable();// true
            $table->boolean('doP2p')->nullable();// true
            $table->boolean('doSpy')->nullable();// true
            $table->string('snapshotServer')->nullable();// "us7"
            $table->integer('ratingPosition')->nullable();// 0
            $table->boolean('isNew')->nullable();// false
            $table->boolean('isLive')->nullable();// true
            $table->integer('hallOfFamePosition')->nullable();// 36
            $table->boolean('isPornStar')->nullable();// false
            $table->string('broadcastCountry')->nullable();// "ca"
            $table->string('username')->nullable();// "JordanXo"
            $table->string('login')->nullable();// "JordanXo"
            $table->string('domain')->nullable();// ""
            $table->string('gender')->nullable();// "female"
            $table->string('genderDoc')->nullable();// "female"
            $table->string('showTokensTo')->nullable();// "models"
            $table->text('offlineStatus')->nullable();// "Stay updated on my life and follow me! https://allmylinks.com/jordanxo"
            $table->string('offlineStatusUpdatedAt')->nullable();// "2021-03-30T03:01:51Z"
            $table->boolean('isOnline')->nullable();// true
            $table->boolean('isBlocked')->nullable();// false
            $table->text('avatarUrl')->nullable();// "https://cdn.strpst.com/cdn/avatars/f/4/6/f4619622cb880a6bdac106275c018fb0-full"
            $table->text('avatarUrlThumb')->nullable();// "https://cdn.strpst.com/cdn/avatars/f/4/6/f4619622cb880a6bdac106275c018fb0-thumb"
            $table->boolean('isRegular')->nullable();// false
            $table->boolean('isExGreen')->nullable();// false
            $table->boolean('isGold')->nullable();// false
            $table->boolean('isUltimate')->nullable();// false
            $table->boolean('isGreen')->nullable();// false
            $table->boolean('hasVrDevice')->nullable();// false
            $table->boolean('isModel')->nullable();// true
            $table->boolean('isStudio')->nullable();// false
            $table->boolean('isAdmin')->nullable();// false
            $table->boolean('isSupport')->nullable();// false
            $table->boolean('isFinance')->nullable();// false
            $table->boolean('isOfflinePrivateAvailable')->nullable();// false
            $table->boolean('isApprovedModel')->nullable();// true
            $table->boolean('isDisplayedModel')->nullable();// true
            $table->boolean('hasAdminBadge')->nullable();// false
            $table->boolean('isPromo')->nullable();// false
            $table->boolean('isUnThrottled')->nullable();// true
            $table->string('userRanking')->nullable();// null
            $table->string('snapshotTimestamp')->nullable();// 1648172135
            $table->string('contestGender')->nullable();// "female"

            $table->timestamps();

            // $table->string('hallOfFame');// {#178 ▶}
            // $table->string('languages');// array:1 [▶]
            // $table->string('specifics');// array:2 [▶]
            // $table->string('interests');// array:15 [▶]
            // $table->string('publicActivities');// array:9 [▶]
            // $table->string('privateActivities');// array:8 [▶]
            // $table->string('exclusivePrivateActivities');// array:13 [▶]
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
