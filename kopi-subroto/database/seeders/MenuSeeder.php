<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tidak perlu truncate karena fresh migration sudah membersihkan

        // Data Menu Coffee
        $coffeeMenus = [
            [
                'name' => 'Espresso',
                'price' => 18000,
                'image' => 'espresso.jpg',
                'description' => 'Kopi hitam pekat dengan aroma kuat.',
                'category' => 'coffee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cappuccino',
                'price' => 25000,
                'image' => 'cappuccino.jpg',
                'description' => 'Paduan espresso, susu, dan foam lembut.',
                'category' => 'coffee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cafe Latte',
                'price' => 28000,
                'image' => 'cafe_latte.jpg',
                'description' => 'Espresso dengan dominasi susu yang creamy.',
                'category' => 'coffee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Americano',
                'price' => 20000,
                'image' => 'americano.jpg',
                'description' => 'Espresso klasik yang dilarutkan air panas.',
                'category' => 'coffee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Caramel Macchiato',
                'price' => 32000,
                'image' => 'caramel_macchiato.jpg',
                'description' => 'Sentuhan manis sirup karamel dan vanila.',
                'category' => 'coffee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mocha Latte',
                'price' => 30000,
                'image' => 'mocha_latte.jpg',
                'description' => 'Perpaduan sempurna antara kopi dan coklat.',
                'category' => 'coffee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Data Menu Snack
        $snackMenus = [
            [
                'name' => 'Croissant Butter',
                'price' => 22000,
                'image' => 'croissant.jpg',
                'description' => 'Croissant dengan mentega premium yang lembut.',
                'category' => 'snack',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Donut Coklat',
                'price' => 15000,
                'image' => 'donut.jpg',
                'description' => 'Donut empuk dengan topping coklat premium.',
                'category' => 'snack',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roti Bakar Selai',
                'price' => 12000,
                'image' => 'roti_bakar.jpg',
                'description' => 'Roti bakar dengan selai strawberry premium.',
                'category' => 'snack',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sandwich Tuna',
                'price' => 28000,
                'image' => 'sandwich.jpg',
                'description' => 'Sandwich berisi tuna segar dengan mayo premium.',
                'category' => 'snack',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Data Menu Lainnya
        $othersMenus = [
            [
                'name' => 'Jus Jeruk Segar',
                'price' => 16000,
                'image' => 'jus_jeruk.jpg',
                'description' => 'Jus jeruk asli tanpa pengawet.',
                'category' => 'others',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teh Manis',
                'price' => 8000,
                'image' => 'teh_manis.jpg',
                'description' => 'Teh segar dengan gula halus.',
                'category' => 'others',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smoothie Strawberry',
                'price' => 24000,
                'image' => 'smoothie.jpg',
                'description' => 'Smoothie strawberry dengan yogurt alami.',
                'category' => 'others',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert semua data
        DB::table('menus')->insert(array_merge($coffeeMenus, $snackMenus, $othersMenus));
    }
}
