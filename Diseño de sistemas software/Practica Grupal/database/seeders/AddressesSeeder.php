<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            [
                'street' => '123 Main St',
                'city' => 'Springfield',
                'province' => 'Illinois',
                'postal_code' => 62701,
                'country' => 'USA',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => '456 Elm St',
                'city' => 'Shelbyville',
                'province' => 'Illinois',
                'postal_code' => 62565,
                'country' => 'USA',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => '789 Oak St',
                'city' => 'Toronto',
                'province' => 'Ontario',
                'postal_code' => 75001,
                'country' => 'Canada',
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => '101 Maple St',
                'city' => 'London',
                'province' => 'Greater London',
                'postal_code' => 20001,
                'country' => 'UK',
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => '202 Pine St',
                'city' => 'Sydney',
                'province' => 'New South Wales',
                'postal_code' => 2000,
                'country' => 'Australia',
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => '303 Birch St',
                'city' => 'Tokyo',
                'province' => 'Tokyo',
                'postal_code' => 1000001,
                'country' => 'Japan',
                'user_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'street' => '404 Cherry St',
                'city' => 'Nairobi',
                'province' => 'Nairobi County',
                'postal_code' => 100,
                'country' => 'Kenya',
                'user_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
