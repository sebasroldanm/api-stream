<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mods', function (Blueprint $table) {
            $table->id();
            $table->text('snapshotUrl')->nullable();//: "https://img.strpst.com/us7/previews/1648172099/47565663"
            $table->text('widgetPreviewUrl')->nullable();//: "https://img.strpst.com/us7/previews/1648172099/47565663"
            $table->integer('privateRate')->nullable();//: 90
            $table->integer('p2pRate')->nullable();//: 120
            $table->boolean('isNonNude')->nullable();//: false
            $table->text('avatarUrl')->nullable();//: "https://cdn.strpst.com/cdn/avatars/f/4/6/f4619622cb880a6bdac106275c018fb0-full"
            $table->boolean('isPornStar')->nullable();//: false
            $table->string('id_mod')->unique();//: 47565663
            $table->string('country')->nullable();//: "ca"
            $table->boolean('doSpy')->nullable();//: true
            $table->boolean('doPrivate')->nullable();//: true
            $table->string('gender')->nullable();//: "female"
            $table->boolean('isHd')->nullable();//: true
            $table->boolean('isVr')->nullable();//: false
            $table->boolean('is2d')->nullable();//: true
            $table->boolean('isExternalApp')->nullable();//: true
            $table->boolean('isMobile')->nullable();//: false
            $table->boolean('isModel')->nullable();//: true
            $table->boolean('isNew')->nullable();//: false
            $table->boolean('isLive')->nullable();//: true
            $table->boolean('isOnline')->nullable();//: true
            $table->text('previewUrl')->nullable();//: "https://cdn.strpst.com/cdn/previews/a/6/b/a6b639d8bbea13f10b9e3765239e6257-full"
            $table->text('previewUrlThumbBig')->nullable();//: "https://cdn.strpst.com/cdn/previews/a/6/b/a6b639d8bbea13f10b9e3765239e6257-thumb-big"
            $table->text('previewUrlThumbSmall')->nullable();//: "https://cdn.strpst.com/cdn/previews/a/6/b/a6b639d8bbea13f10b9e3765239e6257-thumb-small"
            $table->string('broadcastServer')->nullable();//: "us12"
            $table->string('broadcastGender')->nullable();//: "female"

            $table->string('snapshotServer')->nullable();//: "us7"
            $table->string('status')->nullable();//: "public"

            $table->string('topBestPlace')->nullable();//: 2

            $table->string('username')->nullable();//: "JordanXo"
            $table->string('statusChangedAt')->nullable();//: "2022-03-24T23:55:35Z"
            $table->integer('spyRate')->nullable();//: 32
            $table->integer('publicRecordingsRate')->nullable();//: 44

            $table->string('genderGroup')->nullable();//: "F"
            $table->string('popularSnapshotTimestamp')->nullable();//: 1648171563
            $table->boolean('hasGroupShowAnnouncement')->nullable();//: false
            $table->string('groupShowType')->nullable();//: ""
            $table->integer('hallOfFamePosition')->nullable();//: 36
            $table->string('snapshotTimestamp')->nullable();//: "1648172099"
            $table->text('hlsPlaylist')->nullable();//: "https://b-hls-08.doppiocdn.com/hls/47565663_240p/47565663_240p.m3u8"
            $table->boolean('isAvatarApproved')->nullable();//: true
            $table->boolean('isTagVerified')->nullable();//: false
            $table->timestamps();

            // $table->string('broadcastSettings');//: {#30 ▶}
            // $table->string('tags');//: array:43 [▶]
            // $table->string('hallOfFame');//: {#17 ▶}
            // $table->string('languages');//: array:1 [▶]
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mods');
    }
}
