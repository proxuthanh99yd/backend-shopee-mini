<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('brands')->truncate();

        $createMultiple = [
            [
                'name' => 'samsung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'apple',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'xiaomi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'oppo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'vivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'realme',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'nokia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'huawei',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'google',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Brand::insert($createMultiple); // Eloquent

        Schema::enableForeignKeyConstraints();
    }
}
