<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\FlowerMeaning;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Mula Mula',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Budi Customer',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $cat1 = Category::create(['name' => 'Wisuda']);
        $cat2 = Category::create(['name' => 'Ulang Tahun']);
        $cat3 = Category::create(['name' => 'Hari Spesial']);
        $cat4 = Category::create(['name' => 'Artificial']);

        Product::create([
            'category_id' => $cat1->id,
            'name' => 'Red Romance Bouquet',
            'price' => 150000,
            'stock' => 20,
            'description' => 'Buket mawar merah premium untuk mengungkapkan cinta.',
        ]);

        Product::create([
            'category_id' => $cat2->id,
            'name' => 'White Elegance Tulip',
            'price' => 250000,
            'stock' => 10,
            'description' => 'Buket tulip putih bersih lambang ketulusan.',
        ]);

        Product::create([
            'category_id' => $cat3->id,
            'name' => 'Bright Scholar Sunflower',
            'price' => 180000,
            'stock' => 15,
            'description' => 'Buket matahari cerah cocok untuk perayaan kelulusan.',
        ]);

        Product::create([
            'category_id' => $cat4->id,
            'name' => 'Artificial Rose Bouquet',
            'price' => 120000,
            'stock' => 25,
            'description' => 'Buket mawar buatan yang indah dan tahan lama.',
        ]);

        FlowerMeaning::create([
            'flower_name' => 'Mawar Merah',
            'symbolism' => 'Cinta Sejati & Keberanian',
            'description' => 'Melambangkan rasa cinta yang mendalam, gairah, dan rasa hormat yang tinggi kepada pasangan.'
        ]);

        FlowerMeaning::create([
            'flower_name' => 'Tulip Putih',
            'symbolism' => 'Permintaan Maaf & Ketulusan',
            'description' => 'Sering digunakan untuk mengekspresikan rasa hormat, ketulusan hati, atau permohonan maaf yang mendalam.'
        ]);
    }
}