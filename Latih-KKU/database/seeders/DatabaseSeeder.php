<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::create([
            'name'=> 'Yanuar',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

         User::create([
            'name'=> 'Indra',
            'email' => 'petugas@gmail.com',
            'role' => 'petugas',
            'password' => bcrypt('password'),
        ]);

         User::create([
            'name'=> 'salman',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);
    }
}
