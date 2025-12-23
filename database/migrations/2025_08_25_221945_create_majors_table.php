<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('major'); // اسم التخصص
            $table->string('major_code')->nullable(); // كود للتخصص
            $table->string('faculty')->nullable(); // الكلية التابعة لها
            $table->string('faculty_code')->nullable(); // البرنامج الأكاديمي
            $table->text('description')->nullable(); // وصف اختياري
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
