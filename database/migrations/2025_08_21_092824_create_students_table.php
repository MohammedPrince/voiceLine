<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Students table (from dump). No timestamps in SQL.  :contentReference[oaicite:2]{index=2}
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            // `stud_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            // Use signed int to mirror the dump exactly
            $table->integer('stud_id')->autoIncrement();

            $table->string('stud_name', 100);    // NOT NULL
            $table->string('stud_surname', 100); // NOT NULL
            $table->string('familyname', 100)->nullable();
            $table->string('status_code', 10)->nullable();
            $table->integer('curr_sem')->nullable();
            $table->string('faculty_code', 10)->nullable();
            $table->string('major_code', 10)->nullable();
            $table->string('batch', 10)->nullable();
            $table->decimal('stud_gpa', 3, 2)->nullable();
            $table->decimal('stud_cgpa', 3, 2)->nullable();

            // No created_at/updated_at per dump
            $table->primary('stud_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
