<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(['Preloved', 'Food', 'Beverage', 'Service']);

        $products = [
            'Preloved' => [
                ['name' => 'Kemeja Flannel Bekas',        'image' => 'https://picsum.photos/seed/flannel-shirt/640/480'],
                ['name' => 'Sepatu Sneakers Preloved',    'image' => 'https://picsum.photos/seed/sneakers/640/480'],
                ['name' => 'Tas Ransel Kuliah',           'image' => 'https://picsum.photos/seed/backpack/640/480'],
                ['name' => 'Buku Kalkulus Bekas',         'image' => 'https://picsum.photos/seed/textbook/640/480'],
                ['name' => 'Jaket Hoodie Thrift',         'image' => 'https://picsum.photos/seed/hoodie/640/480'],
                ['name' => 'Kipas Angin Bekas',           'image' => 'https://picsum.photos/seed/fan-electric/640/480'],
                ['name' => 'Kalkulator Casio Preloved',   'image' => 'https://picsum.photos/seed/calculator/640/480'],
                ['name' => 'Helm Bogo Bekas',             'image' => 'https://picsum.photos/seed/helmet/640/480'],
                ['name' => 'Celana Jeans Bekas',          'image' => 'https://picsum.photos/seed/jeans/640/480'],
                ['name' => 'Meja Lipat Kos',              'image' => 'https://picsum.photos/seed/folding-table/640/480'],
            ],
            'Food' => [
                ['name' => 'Nasi Goreng Spesial',         'image' => 'https://picsum.photos/seed/fried-rice/640/480'],
                ['name' => 'Ayam Geprek Pedas',           'image' => 'https://picsum.photos/seed/fried-chicken/640/480'],
                ['name' => 'Pisang Keju Crispy',          'image' => 'https://picsum.photos/seed/banana-cheese/640/480'],
                ['name' => 'Seblak Mercon',               'image' => 'https://picsum.photos/seed/seblak-spicy/640/480'],
                ['name' => 'Sate Ayam Madura',            'image' => 'https://picsum.photos/seed/chicken-satay/640/480'],
                ['name' => 'Roti Bakar Coklat Keju',      'image' => 'https://picsum.photos/seed/toast-chocolate/640/480'],
                ['name' => 'Mie Goreng Telur',            'image' => 'https://picsum.photos/seed/noodles-egg/640/480'],
                ['name' => 'Salad Buah Segar',            'image' => 'https://picsum.photos/seed/fruit-salad/640/480'],
                ['name' => 'Martabak Manis',              'image' => 'https://picsum.photos/seed/martabak-sweet/640/480'],
                ['name' => 'Nasi Padang Rendang',         'image' => 'https://picsum.photos/seed/rendang-rice/640/480'],
            ],
            'Beverage' => [
                ['name' => 'Es Kopi Susu Aren',           'image' => 'https://picsum.photos/seed/iced-coffee/640/480'],
                ['name' => 'Matcha Latte',                'image' => 'https://picsum.photos/seed/matcha-latte/640/480'],
                ['name' => 'Boba Brown Sugar',            'image' => 'https://picsum.photos/seed/bubble-tea/640/480'],
                ['name' => 'Es Teh Kampul',               'image' => 'https://picsum.photos/seed/iced-tea/640/480'],
                ['name' => 'Jus Alpukat',                 'image' => 'https://picsum.photos/seed/avocado-juice/640/480'],
                ['name' => 'Thai Tea Dingin',             'image' => 'https://picsum.photos/seed/thai-tea/640/480'],
                ['name' => 'Es Jeruk Peras',              'image' => 'https://picsum.photos/seed/orange-juice/640/480'],
                ['name' => 'Smoothie Strawberry',         'image' => 'https://picsum.photos/seed/strawberry-smoothie/640/480'],
                ['name' => 'Taro Milk Tea',               'image' => 'https://picsum.photos/seed/taro-milk-tea/640/480'],
                ['name' => 'Es Cincau Susu',              'image' => 'https://picsum.photos/seed/milk-grass-jelly/640/480'],
            ],
            'Service' => [
                ['name' => 'Jasa Desain Poster',          'image' => 'https://picsum.photos/seed/graphic-design/640/480'],
                ['name' => 'Jasa Ketik Makalah',          'image' => 'https://picsum.photos/seed/typing-laptop/640/480'],
                ['name' => 'Jasa Install Ulang Laptop',   'image' => 'https://picsum.photos/seed/laptop-repair/640/480'],
                ['name' => 'Jasa Cuci Sepatu',            'image' => 'https://picsum.photos/seed/shoe-cleaning/640/480'],
                ['name' => 'Jasa Terjemah Jurnal',        'image' => 'https://picsum.photos/seed/translation-journal/640/480'],
                ['name' => 'Jasa Pembuatan CV',           'image' => 'https://picsum.photos/seed/resume-cv/640/480'],
                ['name' => 'Jasa Print Antar Kos',        'image' => 'https://picsum.photos/seed/printer-delivery/640/480'],
                ['name' => 'Jasa Edit Video',             'image' => 'https://picsum.photos/seed/video-editing/640/480'],
                ['name' => 'Jasa Joki Tugas',             'image' => 'https://picsum.photos/seed/homework-help/640/480'],
                ['name' => 'Jasa Bersih Kos',             'image' => 'https://picsum.photos/seed/room-cleaning/640/480'],
            ],
        ];

        $selected = fake()->randomElement($products[$category]);

        return [
            'name'      => $selected['name'],
            'description' => fake()->paragraph(),
            'price'     => fake()->randomFloat(2, 1000, 100000),
            'stock'     => fake()->numberBetween(1, 100),
            'category'  => $category,
            'condition' => fake()->randomElement(['New', 'Like New', 'Good', 'Fair']),
            'image_url' => $selected['image'],
        ];
    }
}
