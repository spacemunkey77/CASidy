<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlarmTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_events', function (Blueprint $table) {
            $table->id();
            $table->integer('zone');
            $table->integer('event');
            $table->timestamps();
        });

        Schema::create('alarm_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('zone');
            $table->integer('pin');
            $table->timestamps();
        });

        Schema::create('alarm_settings', function (Blueprint $table) {
            $table->id();
            $table->json('settings');
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
        Schema::dropIfExists('alarm_events');
        Schema::dropIfExists('alarm_zones');
        Schema::dropIfExists('alarm_settings');
    }
}
