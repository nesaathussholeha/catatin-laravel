<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder Admin
        User::create([
            'name' => 'Admin CatatIn',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin12345'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Seeder User Testing
        $user = User::create([
            'name' => 'User Testing',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_number' => '000000000000001',
        ]);

        // Seeder Mahasiswa 1 - Afifatul Mardliyah
        $afifatul = User::create([
            'name' => 'Afifatul Mardliyah',
            'username' => 'afifatulmardliyah',
            'email' => 'afifatul@gmail.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $afifatul->id,
            'student_number' => '255150601111032',
        ]);

        // Seeder Mahasiswa 2 - Naswa Salma Rodhiyah
        $naswa = User::create([
            'name' => 'Naswa Salma Rodhiyah',
            'username' => 'naswasalma',
            'email' => 'naswa@gmail.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $naswa->id,
            'student_number' => '255150600111041',
        ]);

        // Seeder Mahasiswa 3 - Nazma Fairuz Maharga
        $nazma = User::create([
            'name' => 'Nazma Fairuz Maharga',
            'username' => 'nazmafairuz',
            'email' => 'nazma@gmail.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $nazma->id,
            'student_number' => '255150601111001',
        ]);

        // Seeder Mahasiswa 4 - Nesa Athussholeha
        $nesa = User::create([
            'name' => 'Nesa Athussholeha',
            'username' => 'nesaathussholeha',
            'email' => 'nesa@gmail.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $nesa->id,
            'student_number' => '255150600111001',
        ]);

        // Seeder Mahasiswa 5 - Sarah Faradila
        $sarah = User::create([
            'name' => 'Sarah Faradila',
            'username' => 'sarahfaradila',
            'email' => 'sarah@gmail.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $sarah->id,
            'student_number' => '255150600111003',
        ]);
    }
}
