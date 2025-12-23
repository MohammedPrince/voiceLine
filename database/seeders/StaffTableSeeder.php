<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('staff')->insert([
            [
                'staff_id' => 'STF001',
                'full_name' => 'Dr. Ahmed Ali',
                'email' => 'ahmed.ali@voiceline.com',
                'phone' => '090000111',
                'department' => 'Computer Science',
                'created_at' => now(),
            ],
            [
                'staff_id' => 'STF002',
                'full_name' => 'Eng. Mariam Khalid',
                'email' => 'mariam.khalid@voiceline.com',
                'phone' => '090000222',
                'department' => 'Electrical Engineering',
                'created_at' => now(),
            ]
        ]);
    }
}
