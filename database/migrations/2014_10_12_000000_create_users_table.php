<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('user_type', ['Administrator', 'Doctor', 'Nurse', 'Receptionist', 'SupportStaff']);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('specialty')->nullable(); // Only for doctors
            $table->string('password', 255);
            $table->decimal('salary')->default(0); //zidt default value n salary hit mabghatshi tkhdem bach bghit n seedi
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
