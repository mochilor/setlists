<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetlistSongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setlist_song', function (Blueprint $table) {
            $table->uuid('setlist_id');
            $table->uuid('song_id');
            $table->integer('act');
            $table->integer('order');

            $table->foreign('setlist_id')->references('id')->on('setlist')->onDelete('cascade');
            $table->foreign('song_id')->references('id')->on('song')->onDelete('cascade');

            $table->unique(['setlist_id', 'song_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setlist_song');
    }
}
