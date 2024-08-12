<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'La Paz',
                'email' => 'lpz@minoil.com.bo',
                'password' => Hash::make('12345678'),
                'city_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Santa Cruz',
                'email' => 'scz@minoil.com.bo',
                'password' => Hash::make('12345678'),
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cochabamba',
                'email' => 'cbb@minoil.com.bo',
                'password' => Hash::make('12345678'),
                'city_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Analista Marketing',
                'email' => 'marketing@minoil.com.bo',
                'password' => Hash::make('12345678'),
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Asignar roles
        $user1 = User::where('email', 'lpz@minoil.com.bo')->first();
        $user2 = User::where('email', 'scz@minoil.com.bo')->first();
        $user3 = User::where('email', 'cbb@minoil.com.bo')->first();
        $user4 = User::where('email', 'marketing@minoil.com.bo')->first();

        // Asignar roles de mercaderistas
        $user1->assignRole('mercaderista');
        $user2->assignRole('mercaderista');
        $user3->assignRole('mercaderista');

        // Asignar rol de analista de marketing
        $user4->assignRole('analista de marketing');
    }
}
