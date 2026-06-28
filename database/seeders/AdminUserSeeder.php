<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sellon.local'],
            [
                'name' => 'Admin SellOn',
                'nim' => '000000000000000', // 15 digit NIM
                'major' => 'Administrator',
                'whatsapp_no' => '081234567890',
                'email_verified_at' => now(), // bypass email verification
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
