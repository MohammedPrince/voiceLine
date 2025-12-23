<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Voice calls table. Has created_at + updated_at (with ON UPDATE).  :contentReference[oaicite:6]{index=6}
     */
    public function up(): void
    {
        Schema::create('voice_calls', function (Blueprint $table) {
            $table->integer('call_id')->autoIncrement(); // PK int(11)

            // ENUMs exactly as in dump
            $table->enum('customer_type', ['student','parent','staff','external']); // NOT NULL
            $table->integer('customer_id')->nullable();

            $table->enum('category', ['academic','technical','administrative','other']); // NOT NULL
            $table->text('description'); // NOT NULL

            $table->enum('status', ['submitted','in_progress','resolved','closed'])->default('submitted');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');

            $table->integer('handled_by_user_id')->nullable();

            // created_at DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('created_at')->useCurrent();

            // updated_at DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary('call_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_calls');
    }
};
