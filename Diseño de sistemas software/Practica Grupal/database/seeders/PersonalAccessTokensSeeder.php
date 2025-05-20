<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PersonalAccessTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        if($user){
            // Añadimos una entrada a esta tabla
            DB::table('personal_access_tokens')->insert([
                'tokenable_type' => 'App\Models\User',
                'tokenable_id' => $user->id,
                'name' => 'Test Token 1',
                'token' => Str::random(64),
                'abilities' => json_encode(['read', 'write']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('personal_access_tokens')->insert([
                'tokenable_type' => 'App\Models\User',
                'tokenable_id' => $user->id,
                'name' => 'Test Token 2',
                'token' => Str::random(64),
                'abilities' => json_encode(['read', 'write']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
    }
}
