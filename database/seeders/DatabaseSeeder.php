<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        // Create new users
        User::create([
            'name' => 'Doctor One',
            'user_type' => 'Doctor',
            'email' => 'doctor1@example.com',
            'password' => bcrypt('password'),
            'specialty' => 'Cardiologie',
            'salary' => 5000.00,
        ]);

        // Insert second doctor with specialty in Dermatologie
        User::create([
            'name' => 'Doctor Two',
            'user_type' => 'Doctor',
            'email' => 'doctor2@example.com',
            'password' => bcrypt('password'),
            'specialty' => 'Dermatologie',
            'salary' => 6000.00,
        ]);

        // Add more user creations as needed
    }
}

