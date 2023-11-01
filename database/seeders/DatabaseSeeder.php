<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        User::factory(10)->create();
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        Product::factory(50)->create();
        $this->call(ClassificationGroupSeeder::class);
        $this->call(ClassifySeeder::class);
        $this->call(ThumbnailSeeder::class);
    }
}
