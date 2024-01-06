<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMiredToNullableLights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lights', function (Blueprint $table) {
            $table->integer('mired')->nullable()->change();
            $table->integer('boundary')->nullable()->change();
            $table->integer('night')->nullable()->change();
            $table->string('color')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lights', function (Blueprint $table) {
            $table->integer('mired')->change();
            $table->integer('boundary')->change();
            $table->integer('night')->change();
            $table->string('color')->change();
        });
    }
}
