<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('user_type', ['Administrator', 'Doctor', 'Nurse', 'Receptionist', 'SupportStaff']);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('specialty')->nullable(); // Only for doctors
            $table->string('password', 255);
            $table->decimal('salary', 8, 2)->default(0); // Ensure proper precision and scale for salary
            $table->unsignedBigInteger('department_id')->nullable(); // Foreign key to departments table, optional for all
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
