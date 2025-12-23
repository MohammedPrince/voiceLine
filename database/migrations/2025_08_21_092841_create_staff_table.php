<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Staff table. Has created_at only.  :contentReference[oaicite:5]{index=5}
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->integer('staff_code')->autoIncrement(); // PK int(11)
            $table->string('staff_id', 20)->nullable();
            $table->string('full_name', 100);               // NOT NULL
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('department', 100)->nullable();

            // created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('created_at')->useCurrent();

            $table->primary('staff_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
