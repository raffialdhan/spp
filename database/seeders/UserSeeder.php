<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Raffi Aldhan',
                'username' => 'admin',
                'email'    => 'admin@sekolah.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Staff / Petugas
        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'name'     => 'Raffi Aldhan',
                'username' => 'petugas',
                'email'    => 'raffi.aldhan@sekolah.com',
                'password' => Hash::make('petugas123'),
                'role'     => 'staff',
            ]
        );

        // Siswa
        User::updateOrCreate(
            ['username' => 'siswa'],
            [
                'name'     => 'Ahmad Fauzi',
                'username' => 'siswa',
                'email'    => 'ahmad.fauzi@sekolah.com',
                'password' => Hash::make('siswa123'),
                'role'     => 'student',
            ]
        );
    }
}
