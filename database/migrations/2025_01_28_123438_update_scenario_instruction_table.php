<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::table('scenario_instruction', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropForeign(['scenario_id']);
            $table->dropForeign(['instruction_id']);
            $table->integer('repeticiones')->default(1)->after('order');
            $table->json('parametros')->nullable()->after('repeticiones');
            $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
            $table->foreign('instruction_id')->references('id')->on('instructions')->onDelete('cascade');
        });
    }
    
    public function down() {
        Schema::table('scenario_instruction', function (Blueprint $table) {
            $table->id();
            $table->dropColumn(['repeticiones', 'parametros']);
        });
    }
};
