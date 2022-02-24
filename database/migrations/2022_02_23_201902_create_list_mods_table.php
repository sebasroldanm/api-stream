<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListModsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_mods', function (Blueprint $table) {
            $table->id();
            $table->string('nickname');
            $table->integer('user_id')->nullable();
            $table->text('description')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('state')->nullable();
            $table->text('stream')->nullable();
            $table->boolean('isMobile')->nullable();
            $table->string('broadcastGender')->nullable();
            $table->string('previewUrl')->nullable();
            $table->string('previewUrlThumbBig')->nullable();
            $table->string('previewUrlThumbSmall')->nullable();
            $table->string('avatarUrl')->nullable();
            $table->string('avatarUrlThumb')->nullable();
            $table->dateTime('offlineStatusUpdatedAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_mods');
    }
}
