<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Fee;
use App\Models\Student;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DataMasterSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('username', 'admin')->first();
        $staffUser = User::where('username', 'petugas')->first();
        $class12 = ClassRoom::where('name', 'XII - MIPA 1')->first();
        $class11 = ClassRoom::where('name', 'XI - MIPA 1')->first();
        $feeSpp = Fee::where('name', 'SPP Bulanan 2026/2027')->first();

        // 0. Link Admin & Staff to student records for testing
        if ($adminUser && $class12) {
            Student::firstOrCreate(
                ['user_id' => $adminUser->id],
                [
                    'nisn' => '1234567891',
                    'nis' => '12345671',
                    'name' => 'Raffi Aldhan (Admin)',
                    'class_room_id' => $class12->id,
                    'phone' => '081122334455',
                    'academic_year' => '2024',
                    'fee_id' => $feeSpp->id ?? null
                ]
            );
        }

        if ($staffUser && $class11) {
            Student::firstOrCreate(
                ['user_id' => $staffUser->id],
                [
                    'nisn' => '1234567892',
                    'nis' => '12345672',
                    'name' => 'Raffi Aldhan (Staff)',
                    'class_room_id' => $class11->id,
                    'phone' => '081122334466',
                    'academic_year' => '2025',
                    'fee_id' => $feeSpp->id ?? null
                ]
            );
        }
        // 1. Create Classes
        $classes = [
            ['name' => 'X - MIPA 1', 'major' => 'MIPA'],
            ['name' => 'X - MIPA 2', 'major' => 'MIPA'],
            ['name' => 'X - IPS 1', 'major' => 'IPS'],
            ['name' => 'X - IPS 2', 'major' => 'IPS'],
            ['name' => 'XI - MIPA 1', 'major' => 'MIPA'],
            ['name' => 'XI - IPS 1', 'major' => 'IPS'],
            ['name' => 'XII - MIPA 1', 'major' => 'MIPA'],
        ];

        foreach ($classes as $c) {
            ClassRoom::firstOrCreate(['name' => $c['name']], $c);
        }

        // 2. Create Fees
        $fees = [
            [
                'name' => 'SPP Bulanan 2026/2027',
                'type' => 'Bulanan',
                'amount' => 500000,
                'description' => 'Tagihan rutin yang akan di-generate otomatis setiap tanggal 1 awal bulan.',
                'is_active' => true
            ],
            [
                'name' => 'Dana Bangunan',
                'type' => 'Sekali Bayar',
                'amount' => 1000000,
                'description' => 'Tagihan wajib untuk siswa baru Kelas X pada saat pendaftaran awal.',
                'is_active' => true
            ],
        ];

        foreach ($fees as $f) {
            Fee::firstOrCreate(['name' => $f['name']], $f);
        }

        // 3. Create initial Student (link to existing User 'siswa')
        $userSiswa = User::where('username', 'siswa')->first();
        $class12 = ClassRoom::where('name', 'XII - MIPA 1')->first();
        $feeSpp = Fee::where('name', 'SPP Bulanan 2026/2027')->first();

        if ($userSiswa && $class12) {
            Student::firstOrCreate(
                ['nisn' => '9988776655'],
                [
                    'user_id' => $userSiswa->id,
                    'nisn' => '9988776655',
                    'nis' => '12345678',
                    'name' => 'Ahmad Fauzi',
                    'class_room_id' => $class12->id,
                    'phone' => '081234567890',
                    'address' => 'Jl. Pendidikan No. 123',
                    'academic_year' => '2024',
                    'fee_id' => $feeSpp->id ?? null
                ]
            );
        }
        
        // Add more dummy students
        $class10 = ClassRoom::where('name', 'X - IPS 2')->first();
        if ($class10) {
            Student::firstOrCreate(
                ['nisn' => '1122334455'],
                [
                    'nisn' => '1122334455',
                    'nis' => '87654321',
                    'name' => 'Budi Santoso',
                    'class_room_id' => $class10->id,
                    'phone' => '089876543210',
                    'address' => 'Jl. Merdeka No. 45',
                    'academic_year' => '2026',
                    'fee_id' => $feeSpp->id ?? null
                ]
            );
        }

        // 4. Create initial Payments
        $studentAhmad = Student::where('name', 'Ahmad Fauzi')->first();
        $studentBudi = Student::where('name', 'Budi Santoso')->first();
        $adminUser = User::where('role', 'admin')->first();

        if ($studentAhmad && $feeSpp) {
            Payment::firstOrCreate(
                ['student_id' => $studentAhmad->id, 'fee_id' => $feeSpp->id, 'payment_date' => now()->subDays(1)],
                [
                    'amount' => $feeSpp->amount,
                    'status' => 'pending',
                    'note' => 'Titip bayar lewat teller oleh ibu saya, mohon dicek ya bu.',
                    'proof_img' => 'https://images.unsplash.com/photo-1621416894569-0f39ed31d247?auto=format&fit=crop&w=600&q=80'
                ]
            );
        }

        if ($studentBudi && $feeSpp) {
            Payment::firstOrCreate(
                ['student_id' => $studentBudi->id, 'fee_id' => $feeSpp->id, 'payment_date' => now()],
                [
                    'amount' => $feeSpp->amount,
                    'status' => 'success',
                    'verified_by' => $adminUser->id ?? null,
                ]
            );
        }
    }
}
