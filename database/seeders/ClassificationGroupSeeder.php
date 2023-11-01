<?php

namespace Database\Seeders;

use App\Models\ClassificationGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClassificationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('classification_groups')->truncate();
        $createMultiple = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($createMultiple, [
                'name' => 'Memory',
                'product_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        ClassificationGroup::insert($createMultiple); // Eloquent

        Schema::enableForeignKeyConstraints();
    }
}
