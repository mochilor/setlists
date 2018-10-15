<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setlist', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name')->unique();
            $table->date('date')->nullable();
            $table->timestamp('creation_date')->nullable();
            $table->timestamp('update_date')->nullable();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setlist');
    }
}
