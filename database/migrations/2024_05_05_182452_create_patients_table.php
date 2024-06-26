<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('contact_number', 20);
            $table->string('address', 255);
            $table->timestamps();
        });
    }
    public function run()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
