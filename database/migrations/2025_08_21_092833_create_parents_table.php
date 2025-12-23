<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Parents table with FK to students(stud_id). Has created_at only.  :contentReference[oaicite:3]{index=3}
     */
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->integer('parent_id')->autoIncrement(); // PK int(11)
            $table->string('full_name', 100);              // NOT NULL
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('relation_to_student', 100)->nullable();
            $table->integer('stud_id')->nullable();        // FK to students.stud_id

            // created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('created_at')->useCurrent();

            $table->primary('parent_id');
            $table->index('stud_id'); // dump has KEY `stud_id`  :contentReference[oaicite:4]{index=4}

            // Exact FK from dump:
            $table->foreign('stud_id')->references('stud_id')->on('students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
