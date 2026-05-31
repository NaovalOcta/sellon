<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            $products = \App\Models\Product::factory(10)->create([
                'user_id' => $user->id,
            ]);

            foreach ($products as $product) {
                \App\Models\ProductImage::factory(3)->create([
                    'product_id' => $product->id,
                ]);
            }
        });
    }
}
