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
        Schema::create('athlete', function (Blueprint $table) {
            $table->string('athlete_id')->primary();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->char('sex')->nullable();
            $table->boolean('hide_private_data')->nullable();
            // Auto set next sync date to one hour from now
            $table->timestamp('next_sync_time')->default(date("Y-m-d H:i:s", time() + 3600));
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
        Schema::dropIfExists('athlete');
    }
};
