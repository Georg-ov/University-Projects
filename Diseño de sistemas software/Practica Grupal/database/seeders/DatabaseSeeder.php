<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $this->call( UsersTableSeeder ::class );
        $this->command->info('User table seeded!');

        $this->call( AddressesSeeder ::class);
        $this->command->info('Addresses table seeded!');

        $this->call( CategoriesTableSeeder ::class);
        $this->command->info('Categories table seeded!');

        $this->call( CoursesTableSeeder ::class);
        $this->command->info('Course table seeded!');

        $this->call( LessonsTableSeeder ::class);
        $this->command->info('Lessons table seeded!');

        $this->call( PersonalAccessTokensSeeder ::class);
        $this->command->info('PersonalAccesTokens table seeded!');

        $this->call( CreditCardSeeder ::class);
        $this->command->info('CreditCard table seeded!');

        $this->call( CommentsSeeder ::class);
        $this->command->info('Comments table seeded!');
    }
}
