<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->string('activity_id')->unique();
            $table->string('athlete_id');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('elapsed_time')->nullable();
            $table->float('distance')->nullable();
            $table->integer('total_elevation_game')->nullable();
            $table->string('start_date')->nullable();
            $table->string('start_date_local')->nullable();
            $table->integer('utc_offset')->nullable();
            $table->integer('kudos_count')->nullable();
            $table->timestamps();

            // Declare our foreign key
            $table->foreign('athlete_id')->references('athlete_id')->on('athlete')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity');
    }
};
