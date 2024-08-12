<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cities')->insert([
            ['name' => 'Santa Cruz'],
            ['name' => 'La Paz'],
            ['name' => 'Cochabamba'],
            // Agrega más ciudades si es necesario
        ]);
    }
}
