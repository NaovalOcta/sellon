<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Bulanan',
                'duration_days' => 30,
                'price' => 15000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
