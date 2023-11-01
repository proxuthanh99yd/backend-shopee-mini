<?php

namespace Database\Seeders;

use App\Models\Thumbnail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('thumbnails')->truncate();

        $createMultiple = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($createMultiple, [
                'name' => 'fakeThumbImage-1-'.$i.'.jpg',
                'product_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $createMultiple2 = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($createMultiple2, [
                'name' => 'fakeThumbImage-2-'.$i.'..jpg',
                'product_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $createMultiple3 = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($createMultiple3, [
                'name' => 'fakeThumbImage-3-'.$i.'..jpg',
                'product_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        Thumbnail::insert($createMultiple); // Eloquent
        Thumbnail::insert($createMultiple2);
        Thumbnail::insert($createMultiple3);
        Schema::enableForeignKeyConstraints();
    }
}
