<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::table('instructions', function (Blueprint $table) {
            $table->dropColumn(['text_content', 'require_action', 'action_type', 'waiting_time']);
            $table->string('instruction_name')->after('id');
            $table->text('tts_description')->after('instruction_name');
            $table->string('type')->after('tts_description');
        });
    }
    
    public function down() {
        Schema::table('instructions', function (Blueprint $table) {
            $table->text('text_content');
            $table->boolean('require_action');
            $table->enum('action_type', ['discharge', 'electrodes', 'none']);
            $table->integer('waiting_time');
            $table->dropColumn(['instruction_name', 'tts_description', 'type']);
        });
    }
};
