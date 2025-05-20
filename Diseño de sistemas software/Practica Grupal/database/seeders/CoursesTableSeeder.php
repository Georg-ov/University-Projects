<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            [
                'name' => 'Introduction to Python',
                'description' => 'Learn the basics of Python programming.',
                'image_file_name' => 'course_images/PythonCourse.jpeg',
                'visibility' => true,
                'publish_date' => '2024-01-01',
                'user_id' => 4,
                'category_id' => 1,
                'is_free' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic guide to ChatGPT.',
                'description' => 'Introduction and tips to start using ChatGPT.',
                'image_file_name' => 'course_images/chatgpt.jpg',
                'visibility' => true,
                'publish_date' => '2024-02-01',
                'user_id' => 4,
                'category_id' => 1,
                'is_free' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Introduction to Flowers',
                'description' => 'Basic names and tips to differentiate flowers.',
                'image_file_name' => 'course_images/BotanicIntroduction.jpg',
                'visibility' => true,
                'publish_date' => '2024-03-01',
                'user_id' => 3,
                'category_id' => 2,
                'is_free' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'How to start playing BlackJack',
                'description' => 'Introduction and pro player tips to ALWAYS win at blackJack!!',
                'image_file_name' => 'course_images/blackjack.jpg',
                'visibility' => true,
                'publish_date' => '2024-04-01',
                'user_id' => 2,
                'category_id' => 3,
                'is_free' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'How to stop gambling',
                'description' => 'A guide to stop gambling. (Only for lossers)',
                'image_file_name' => 'course_images/stopGambling.jpg',
                'visibility' => false,
                'publish_date' => '2024-05-01',
                'user_id' => 2,
                'category_id' => 3,
                'is_free' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

