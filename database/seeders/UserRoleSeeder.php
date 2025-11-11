<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::create([
            'username' => 'admin',
            'nama' => 'akim',
            'password' => '12345678',
            'keterangan' => 'Super Admin Aplikasi',
            'status' => 'aktif',
            'role' => 'super_admin',
        ]);

        UserRole::create([
            'username' => 'teknisi',
            'nama' => 'iwan',
            'password' => '12345678',
            'keterangan' => 'Teknisi Aplikasi',
            'status' => 'aktif',
            'role' => 'teknisi',
        ]);

        UserRole::create(attributes: [
            'username' => 'marketing',
            'nama' => 'naura',
            'password' => '12345678',
            'keterangan' => 'Marketing Aplikasi',
            'status' => 'aktif',
            'role' => 'marketing',
        ]);
    }
}
