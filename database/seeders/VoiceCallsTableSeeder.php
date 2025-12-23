<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoiceCallsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('voice_calls')->insert([
            [
                'customer_type' => 'student',
                'customer_id' => 1,
                'category' => 'academic',
                'description' => 'Inquiry about exam schedule',
                'status' => 'submitted',
                'priority' => 'medium',
                'handled_by_user_id' => null,
                'ticket_number' => 'VCALL-1001',   // <-- new field
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_type' => 'staff',
                'customer_id' => 2,
                'category' => 'technical',
                'description' => 'Issue with online grading system',
                'status' => 'in_progress',
                'priority' => 'high',
                'handled_by_user_id' => 1,
                'ticket_number' => 'VCALL-1002',   // <-- new field
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
