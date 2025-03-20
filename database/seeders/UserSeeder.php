<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'rfid' => null,
        ]);

        // // Create Lecturers
        // User::create([
        //     'name' => 'John Doe',
        //     'email' => 'lecturer1@example.com',
        //     'password' => Hash::make('password123'),
        //     'role' => 'lecturer',
        //     'rfid' => '123456789',
        // ]);

        // User::create([
        //     'name' => 'Jane Smith',
        //     'email' => 'lecturer2@example.com',
        //     'password' => Hash::make('password123'),
        //     'role' => 'lecturer',
        //     'rfid' => '987654321',
        // ]);
    }
}
