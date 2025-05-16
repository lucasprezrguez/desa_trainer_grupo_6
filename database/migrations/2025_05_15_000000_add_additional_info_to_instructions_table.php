<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalInfoToInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructions', function (Blueprint $table) {
            if (!Schema::hasColumn('instructions', 'additional_info')) {
                $table->longText('additional_info')->nullable()->after('require_action');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructions', function (Blueprint $table) {
            if (Schema::hasColumn('instructions', 'additional_info')) {
                $table->dropColumn('additional_info');
            }
        });
    }
} 