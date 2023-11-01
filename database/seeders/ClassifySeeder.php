<?php

namespace Database\Seeders;

use App\Models\Classify;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClassifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('classifies')->truncate();
        $createMultiple = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($createMultiple, [
                'name' => '128GB',
                'price' => random_int(500, 800),
                'stock' => 100,
                'classification_group_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $createMultiple2 = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($createMultiple2, [
                'name' => '256GB',
                'price' => random_int(850, 1000),
                'stock' => 100,
                'classification_group_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        Classify::insert($createMultiple); // Eloquent
        Classify::insert($createMultiple2);
        Schema::enableForeignKeyConstraints();
    }
}
