<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('voice_calls', function (Blueprint $table) {
            // Add ticket_number as VARCHAR(50), unique to avoid duplicates
            $table->string('ticket_number', 50)->after('call_id');
        });
    }

    public function down(): void
    {
        Schema::table('voice_calls', function (Blueprint $table) {
            $table->dropColumn('ticket_number');
        });
    }
};
