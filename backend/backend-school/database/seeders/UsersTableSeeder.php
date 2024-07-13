<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            [
                'name' => 'Admin User',
                'phone' => '1234567890',
                'username' => 'adminuser',
                'email' => 'admin@example.com',
                'user_type' => 'admin',
                'address' => '123 Admin St',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Teacher User',
                'phone' => '2345678901',
                'username' => 'teacheruser',
                'email' => 'teacher@example.com',
                'user_type' => 'teacher',
                'address' => '123 Teacher St',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff User',
                'phone' => '3456789012',
                'username' => 'staffuser',
                'email' => 'staff@example.com',
                'user_type' => 'staff',
                'address' => '123 Staff St',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Student User',
                'phone' => '4567890123',
                'username' => 'studentuser',
                'email' => 'student@example.com',
                'user_type' => 'student',
                'address' => '123 Student St',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Parent User',
                'phone' => '5678901234',
                'username' => 'parentuser',
                'email' => 'parent@example.com',
                'user_type' => 'parent',
                'address' => '123 Parent St',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Driver User',
                'phone' => '6789012345',
                'username' => 'driveruser',
                'email' => 'driver@example.com',
                'user_type' => 'driver',
                'address' => '123 Driver St',
                'password' => Hash::make('password'),
            ],
        ];

        // Insert user data into the users table
        DB::table('users')->insert($users);
    }
}
