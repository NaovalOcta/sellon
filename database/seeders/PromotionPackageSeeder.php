<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promotion_packages')->insert([
            [
                'name' => '3 Hari',
                'duration_days' => 3,
                'price_regular' => 5000.00,
                'price_premium' => 3500.00, // 30% discount simulation
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '7 Hari',
                'duration_days' => 7,
                'price_regular' => 10000.00,
                'price_premium' => 7000.00, // 30% discount simulation
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '14 Hari',
                'duration_days' => 14,
                'price_regular' => 20000.00,
                'price_premium' => 14000.00, // 30% discount simulation
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
