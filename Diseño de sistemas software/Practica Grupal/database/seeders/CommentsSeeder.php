<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insertamos comentarios manualmente
        DB::table('comments')->insert([
            [
                'content' => 'Could you upload a tutorial for MAC ussers please.',
                'rating' => 4,
                'date' => '2024-05-08',
                'user_id' => 6,
                'lesson_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Nevermind I got myself a Windows system.',
                'rating' => 5,
                'date' => '2024-05-10',
                'user_id' => 6,
                'lesson_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'content' => 'Loved this class, learned a lot!',
                'rating' => 5,
                'date' => '2024-05-16',
                'user_id' => 6,
                'lesson_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'content' => 'This class was usseless, I already knew everything cuz im the real slim shaddy.',
                'rating' => 1,
                'date' => '2024-05-22',
                'user_id' => 5,
                'lesson_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'content' => 'What about replanting the collected flowers? Could you upload a 4 leaf clover collecting guide?',
                'rating' => 3,
                'date' => '2024-05-20',
                'user_id' => 5,
                'lesson_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'content' => 'Before this video I was so afraid of entering a casino, now that I finally entered one Im ready to be rich!',
                'rating' => 5,
                'date' => '2024-05-10',
                'user_id' => 6,
                'lesson_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'content' => 'M2 wish you the best of lucks!',
                'rating' => 5,
                'date' => '2024-05-11',
                'user_id' => 5,
                'lesson_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'content' => 'I already lost 5k. I will never bet again! I am very sorry for having seen the course.',
                'rating' => 1,
                'date' => '2024-05-16',
                'user_id' => 6,
                'lesson_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agrega más comentarios aquí según sea necesario
        ]);
    }
}
