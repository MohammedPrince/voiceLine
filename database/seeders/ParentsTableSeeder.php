<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('parents')->insert([
            [
                'full_name' => 'Mohammed Hassan',
                'email' => 'mhassan@example.com',
                'phone' => '091111111',
                'relation_to_student' => 'Father',
                'stud_id' => 1, // FK → students.stud_id
                'created_at' => now(),
            ],
            [
                'full_name' => 'Amina Omar',
                'email' => 'aomar@example.com',
                'phone' => '092222222',
                'relation_to_student' => 'Mother',
                'stud_id' => 2,
                'created_at' => now(),
            ]
        ]);
    }
}
