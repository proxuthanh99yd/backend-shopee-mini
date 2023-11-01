<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('categories')->truncate();

        $createMultiple = [
            [
                'name' => 'SmartPhone',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Headphone',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SmartWatch',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tablets',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($createMultiple); // Eloquent

        Schema::enableForeignKeyConstraints();
    }
}
