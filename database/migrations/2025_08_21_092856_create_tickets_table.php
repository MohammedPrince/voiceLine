<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tickets table. Unique ticket_number, created_at + updated_at (ON UPDATE).  :contentReference[oaicite:7]{index=7}
     *
     * NOTE: The SQL dump did NOT declare a foreign key from `voice_call_id` to `voice_calls(call_id)`.
     * If you want one, see the commented lines below.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('ticket_serial_no')->autoIncrement(); // PK int(11)
            $table->integer('voice_call_id');                     // NOT NULL (no FK in dump)

            $table->string('ticket_number', 50)->nullable();      // UNIQUE in dump
            $table->string('ticket_id', 15);                      // NOT NULL

            $table->string('ticket_category', 50)->nullable();
            $table->date('issue_date')->nullable();

            $table->enum('opened_type', ['student','parent','staff'])->default('student');
            $table->integer('opened_by_whois'); // NOT NULL

            // Column name is `Ticket_status` in the dump (capital T)
            $table->enum('Ticket_status', ['open','in_progress','on_hold','resolved','closed'])->default('open');

            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');

            // created_at, updated_at with ON UPDATE behavior
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->text('ticket_url'); // NOT NULL

            $table->primary('ticket_serial_no');
            $table->unique('ticket_number'); // mirror UNIQUE KEY  :contentReference[oaicite:8]{index=8}

            // Optional: add an index for faster joins (not in dump)
            // $table->index('voice_call_id');

            // Optional FK (NOT in dump) — uncomment only if you want to enforce it
            // $table->foreign('voice_call_id')->references('call_id')->on('voice_calls')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
