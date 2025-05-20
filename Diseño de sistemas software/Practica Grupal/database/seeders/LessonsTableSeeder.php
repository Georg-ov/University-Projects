<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lessons')->insert([
            [
                'name' => 'How to install python',
                'description' => 'Tutorial to install and start using python.',
                'video_file_name' => 'lesson_images/python1.jpg',
                'course_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dominate Python',
                'description' => 'Python Features and more.',
                'video_file_name' => 'lesson_images/python2.jpg',
                'course_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'How to use chatpgt',
                'description' => 'Learn everything about the prompts and make yout first contact with chatGPT.',
                'video_file_name' => 'lesson_images/chatgpt1.jpeg',
                'course_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Daisies',
                'description' => 'Everything about daisies.',
                'video_file_name' => 'lesson_images/botanic1.jpg',
                'course_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lilacs',
                'description' => 'Everything about lilacs and how to make infusions with them.',
                'video_file_name' => 'lesson_images/botanic2.jpg',
                'course_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'How to gather flowers',
                'description' => 'Tutorial and tips to gather your favorite flowers.',
                'video_file_name' => 'lesson_images/botanic3.jpg',
                'course_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'How to start playing',
                'description' => 'Tutorial to find a casino and start playing.',
                'video_file_name' => 'lesson_images/gambling1.jpg',
                'course_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'How to join a table and start winning a lot of money',
                'description' => 'Tips and warnings for your first hands.',
                'video_file_name' => 'lesson_images/gambling2.jpg',
                'course_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

             [
                'name' => 'Tips to never lose',
                'description' => 'A quick guide to stop losing games',
                'video_file_name' => 'lesson_images/gambling3.jpg',
                'course_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
