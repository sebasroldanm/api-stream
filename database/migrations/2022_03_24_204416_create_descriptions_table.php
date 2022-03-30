<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mod_id')->unique();//{#177 ▶}
            $table->foreign('mod_id')->references('id')->on('mods');

            $table->boolean('canAddFriends')->nullable();//false
            $table->boolean('isInFavorites')->nullable();//false
            $table->boolean('isPmSubscribed')->nullable();//false
            $table->boolean('isSubscribed')->nullable();//false
            $table->string('subscriptionModel')->nullable();//null
            $table->boolean('isProfileAvailable')->nullable();//true
            $table->string('friendship')->nullable()->nullable();//null
            $table->boolean('isBanned')->nullable();//false
            $table->boolean('isMuted')->nullable();//false
            $table->boolean('isStudioModerator')->nullable();//false
            $table->boolean('isStudioAdmin')->nullable();//false
            $table->boolean('isBannedByKnight')->nullable();//false
            $table->string('banExpiresAt')->nullable()->nullable();//null
            $table->boolean('isGeoBanned')->nullable();//false
            $table->integer('photosCount')->nullable();//33
            $table->integer('videosCount')->nullable();//11
            $table->integer('currPosition')->nullable();//3
            $table->integer('currPoints')->nullable();//40206
            $table->integer('relatedModelsCount')->nullable();//215



            $table->boolean('shouldShowOtherModels')->nullable();//true
            $table->string('previewReviewStatus')->nullable();//""
            $table->boolean('feedAvailable')->nullable();//true


            $table->timestamps();

            // $table->string('socialLinks');//{#180 ▶}
            // $table->string('socialLinksData');//{#182 ▶}
            // $table->string('tagGroups');//array:4 [▶]
            // $table->string('tags');//array:43 [▶]
            // $table->string('lastTagsAliases');//array:43 [▶]
            // $table->string('feed');//{#189 ▶}
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('descriptions');
    }
}
