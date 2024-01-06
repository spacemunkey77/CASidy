<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlarmNotifies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_notifies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('sms');
            $table->string('notify');
            $table->boolean('testing');
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
        Schema::dropIfExists('alarm_notifies');
    }
}
