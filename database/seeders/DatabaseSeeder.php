<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Define the user types
        $userTypes = ['Administrator', 'Doctor', 'Nurse', 'Receptionist', 'SupportStaff'];

        // Create one user for each user type
        foreach ($userTypes as $type) {
            DB::table('users')->insert([
                'name' => ucfirst($type), 
                'user_type' => $type,
                'email' => strtolower($type) . '@example.com', 
                'password' => Hash::make('123123'), 
                'created_at' => now(),
                'updated_at' => now(),
                
            ]);
        }

        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('patients')->insert([
                'name' => $faker->name,
                'dob' => $faker->date,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'contact_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $items = [
            'Dolipran',
            'Paracetamol',
            'Bruffin',
            'Insoline',
            'Meno',
            
        ];

        foreach ($items as $item) {
            DB::table('items')->insert([
                'name' => $item, // Generates a three-word name
                'quantity' => $faker->numberBetween(1, 100),
                'description' => $faker->sentence,
                'dosage' => $faker->randomElement([$faker->word, null]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $departments = [
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Pediatrics',
            'Oncology',
            
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'department_name' => $department,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

