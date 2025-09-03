<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role admin jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Buat akun admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@tokosepatu.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Assign role admin
        $admin->assignRole('admin');

        $this->command->info('Admin account created successfully!');
        $this->command->info('Email: admin@tokosepatu.com');
        $this->command->info('Password: admin123');
    }
}