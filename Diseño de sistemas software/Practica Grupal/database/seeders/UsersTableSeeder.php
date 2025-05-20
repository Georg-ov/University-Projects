<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Address;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('lessons')->delete();

        DB::table('courses')->delete();
        DB::table('addresses')->delete();
        DB::table('users')->delete();

        DB::table('personal_access_tokens')->delete();
        DB::table('categories')->delete();
        DB::table('credit_cards')->delete();

        DB::table('users')->insert([
            [
                'name' => 'Segismundo Ortíz Ordoñez',
                'username' => 'Segismundo Admin',
                'email' => 'eltitosegis@hotmail.com',
                'password' => Hash::make('password'),
                'age' => '1942-08-21',
                'role_type' => 'ADMIN',
                'subscription_type' => 'PREMIUM',
                'subscription_expiration_date' => '2024-12-31',
                'about_me' => 'Im currently administrating this site',
                'image_profile' => 'users_images/Segismundo.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lorraine Martinez Biden',
                'username' => 'Ms Lorraine',
                'email' => 'lorrymolly@tiktok.es',
                'password' => Hash::make('password'),
                'age' => '1985-05-15',
                'role_type' => 'TEACHER',
                'subscription_type' => 'PREMIUM',
                'subscription_expiration_date' => '2025-01-15',
                'about_me' => 'Passionate about teaching the power of luck.',
                'image_profile' => 'users_images/lorraine.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rakim White Williams',
                'username' => 'Rakim the sunflower',
                'email' => 'rakim@yahoo.com',
                'password' => Hash::make('password'),
                'age' => '1992-07-20',
                'role_type' => 'TEACHER',
                'subscription_type' => 'PREMIUM',
                'subscription_expiration_date' => '2024-11-30',
                'about_me' => 'Admin and organizer.',
                'image_profile' => 'users_images/rakim.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zhao Min',
                'username' => 'Zhao Max',
                'email' => 'zhao2019@wechat.com',
                'password' => Hash::make('password'),
                'age' => '1999-03-10',
                'role_type' => 'TEACHER',
                'subscription_type' => 'PREMIUM',
                'subscription_expiration_date' => '2024-08-20',
                'about_me' => 'Avid reader and learner.',
                'image_profile' => 'users_images/zhao.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adrian Suavizante De Paredes',
                'username' => 'adriTrebol69',
                'email' => 'adrisalvajepuntocom@gmail.es',
                'password' => Hash::make('password'),
                'age' => '2003-03-08',
                'role_type' => 'STUDENT',
                'subscription_type' => 'PREMIUM',
                'subscription_expiration_date' => '2024-09-25',
                'about_me' => 'I love learning new things everyday!',
                'image_profile' => 'users_images/adrian.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dante Dick Dickenss',
                'username' => 'Drago the GOAT',
                'email' => 'ddd@youtube.com',
                'password' => Hash::make('password'),
                'age' => '1991-11-05',
                'role_type' => 'STUDENT',
                'subscription_type' => 'PREMIUM',
                'subscription_expiration_date' => '2025-02-28',
                'about_me' => 'I love red dragons.',
                'image_profile' => 'users_images/Drago.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Ignacio Guerrero Mesalina',
                'username' => 'cojinDestructor',
                'email' => 'cojindestructor@whatsapp.es',
                'password' => Hash::make('password'),
                'age' => '2002-03-01',
                'role_type' => 'STUDENT',
                'subscription_type' => 'FREEMIUM',
                'subscription_expiration_date' => '2025-02-28',
                'about_me' => 'Admin please upgrade me to PREEMIUM, im poor, I lost it everything in the casino :(',
                'image_profile' => 'users_images/ignacio.png',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
