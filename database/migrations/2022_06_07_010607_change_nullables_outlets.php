<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNullablesOutlets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->boolean('boundary')->nullable()->change();
            $table->boolean('night')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->boolean('boundary')->change();
            $table->boolean('night')->change();
        });
    }
}
