<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up() {
    //     Schema::table('instructions', function (Blueprint $table) {
    //         $table->dropColumn(['text_content', 'action_type']);
    //         $table->string('instruction_name')->after('id');
    //         $table->text('tts_description')->after('instruction_name');
    //         $table->string('type')->after('tts_description');
    //     });
    // }
    
    // public function down() {
    //     Schema::table('instructions', function (Blueprint $table) {
    //         $table->text('text_content');
    //         $table->enum('action_type', ['discharge', 'electrodes', 'none']);
    //         $table->dropColumn(['instruction_name', 'tts_description', 'type']);
    //     });
    // }
};
