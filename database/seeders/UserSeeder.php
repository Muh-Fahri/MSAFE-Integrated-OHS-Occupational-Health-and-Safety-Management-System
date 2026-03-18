<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Manager Safety (HOD) - ID 10
        User::updateOrCreate(
            ['id' => 10],
            [
                'username' => 'manager.safety',
                'name'     => 'Manager Safety (HOD)',
                'email'    => 'hod@example.com',
                'password' => Hash::make('password123'),
                'role_id'  => 5, // Tetap Role 3 (HOD)
            ]
        );

        // 2. Karyawan Lapangan - ID 2
        User::updateOrCreate(
            ['id' => 2],
            [
                'username' => 'karyawan.lapangan',
                'name'     => 'Karyawan Lapangan',
                'email'    => 'user@example.com',
                'password' => Hash::make('password123'),
                'role_id'  => 4, // Role 2 (User)
                'hod'      => 10,
            ]
        );

        // 3. OHS Supervisor - ID 45
        User::updateOrCreate(
            ['id' => 45],
            [
                'username' => 'ohs.budi',
                'name'     => 'Budi OHS',
                'email'    => 'budi.ohs@example.com',
                'password' => Hash::make('password123'),
                'role_id'  => 4, // DIUBAH DARI 4 KE 2
            ]
        );

        // 4. OHS Supervisor - ID 210
        User::updateOrCreate(
            ['id' => 210],
            [
                'username' => 'ohs.siti',
                'name'     => 'Siti OHS',
                'email'    => 'siti.ohs@example.com',
                'password' => Hash::make('password123'),
                'role_id'  => 4, // DIUBAH DARI 4 KE 2
            ]
        );

        // 5. Site Manager - ID 88
        User::updateOrCreate(
            ['id' => 88],
            [
                'username' => 'site.manager',
                'name'     => 'Site Manager Utama',
                'email'    => 'sm@example.com',
                'password' => Hash::make('password123'),
                'role_id'  => 4, // DIUBAH DARI 5 KE 2
            ]
        );



        // 6. User pendukung lainnya menggunakan loop
        $others = [103, 215, 357];
        foreach ($others as $id) {
            User::updateOrCreate(
                ['id' => $id],
                [
                    'username' => 'user.extra.' . $id,
                    'name'     => 'Petugas Evaluasi ' . $id,
                    'email'    => 'evaluator' . $id . '@company.com',
                    'password' => Hash::make('password123'),
                    'role_id'  => 4, // Tetap Role 2 (User)
                    'status'   => 'active',
                ]
            );
        }
    }
}
