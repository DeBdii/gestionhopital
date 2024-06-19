<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Anas Biout',
            'user_type' => 'Administrator',
            'email' => 'A.biout@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

