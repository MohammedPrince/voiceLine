<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed default users
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@voiceline.com',
                'password' => Hash::make('password'), // secure password
                'role' => User::ROLE_ADMIN,
                'created_at' => now(),
                'updated_at' => now(),
                
            ],
            [
                'name' => 'supervisor',
                'email' => 'supervisor@voiceline.com',
                'password' => Hash::make('password'),
                 'role' => User::ROLE_SUPERVISOR,
                'created_at' => now(),
                'updated_at' => now(),
            ],
              [
                'name' => 'Support Agent',
                'email' => 'agent@voiceline.com',
                'password' => Hash::make('password'),
                 'role' => User::ROLE_USER,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
