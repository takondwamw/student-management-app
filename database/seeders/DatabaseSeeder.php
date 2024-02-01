<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    //    User::factory(20)->create();

        \App\Models\User::factory()->create([
            'name' => 'Takondwa Kapyola',
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ]);

        // $this->call([
        //     UserSeeder::class,
        // ]);
    }
}
