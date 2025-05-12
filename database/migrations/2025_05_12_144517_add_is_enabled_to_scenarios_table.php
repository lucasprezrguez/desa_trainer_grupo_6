<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsEnabledToScenariosTable extends Migration
{
    public function up()
    {
        Schema::table('scenarios', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(true);
        });
    }

    public function down()
    {
        Schema::table('scenarios', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
}