<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('students')->insert([
            [
                'stud_name' => 'Ali',
                'stud_surname' => 'Hassan',
                'familyname' => 'Mohammed',
                'status_code' => 'ACT',
                'curr_sem' => 4,
                'faculty_code' => 'ENG',
                'major_code' => 'BIO',
                'batch' => '2020',
                'stud_gpa' => 3.6,
                'stud_cgpa' => 3.4,
            ],
            [
                'stud_name' => 'Sara',
                'stud_surname' => 'Omar',
                'familyname' => 'Abdalla',
                'status_code' => 'ACT',
                'curr_sem' => 2,
                'faculty_code' => 'SCI',
                'major_code' => 'SCI',
                'batch' => '2023',
                'stud_gpa' => 3.2,
                'stud_cgpa' => 3.1,
            ]
        ]);
    }
}
