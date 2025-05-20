<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Computing',
                'description' => 'Tutorials and introducctions to different programming languages.',
                'image_file_name' => '/category_images/Computing.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Botanic',
                'description' => 'Tips to differentiate flowers and guides to collect them in a proper way.',
                'image_file_name' => 'category_images/Botanic.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gambling',
                'description' => 'Tips and tutorial to win a lot of money playing casino games and how to fight the gambling addiction.',
                'image_file_name' => 'category_images/Gambling.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
