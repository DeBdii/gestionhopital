<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id('shift_id');
            $table->string('shift_name', 50);
            $table->dateTime('shift_datetime');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('administrator_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('employee_type', ['Nurse', 'Receptionist', 'SupportStaff']);
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('administrator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};
