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
        Schema::create('auth', function (Blueprint $table) {
            $table->string('athlete_id')->primary();
            $table->string('refresh_token');
            $table->string('access_token');
            $table->integer('valid')->default(1);
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
        Schema::dropIfExists('auth');
    }
};
