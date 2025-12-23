<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tickets')->insert([
            [
                'voice_call_id' => 1,
                'ticket_number' => 'TICK-1001',
                'ticket_id' => 'TK001',
                'ticket_category' => 'technical',
                'issue_date' => now(),
                'opened_type' => 'student',
                'opened_by_whois' => 1,
                'Ticket_status' => 'open',
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
                'ticket_url' => 'http://voiceline.test/tickets/1',
            ],
            [
                'voice_call_id' => 2,
                'ticket_number' => 'TICK-1002',
                'ticket_id' => 'TK002',
                'ticket_category' => 'administrative',
                'issue_date' => now(),
                'opened_type' => 'staff',
                'opened_by_whois' => 2,
                'Ticket_status' => 'in_progress',
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now(),
                'ticket_url' => 'http://voiceline.test/tickets/2',
            ]
        ]);
    }
}
