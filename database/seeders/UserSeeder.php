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
        User::create([
            'name' => 'Admin CatatIn',
            'username' => 'admin',
            'email' => 'admin@catatin.com',
            'password' => Hash::make('Admin12345'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Seeder Mahasiswa 1
        User::create([
            'name' => 'Andi Pratama',
            'username' => 'andipratama',
            'email' => 'andi@example.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        // Seeder Mahasiswa 2
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        // Seeder Mahasiswa 3
        User::create([
            'name' => 'Citra Lestari',
            'username' => 'citralestari',
            'email' => 'citra@example.com',
            'password' => Hash::make('Mahasiswa123'),
            'role' => 'student',
            'status' => 'active',
        ]);
    }
}
