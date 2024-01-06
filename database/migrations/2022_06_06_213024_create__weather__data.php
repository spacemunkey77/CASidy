<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_datas', function (Blueprint $table) {
            $table->id();
            $table->double('TempFahr', 8, 2);
            $table->string('cond');
            $table->string('icon');
            $table->float('cloudcover');
            $table->float('precipChance');
            $table->integer('humidity');
            $table->integer('city');
            $table->time('TimeCollected');
            $table->timestamps();
        });

        Schema::create('weather_cities', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
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
        Schema::dropIfExists('weather_datas');
        Schema::dropIfExists('weather_cities');
    }
}
