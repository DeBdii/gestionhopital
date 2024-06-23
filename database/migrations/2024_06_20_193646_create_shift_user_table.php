<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftUserTable extends Migration
{
    public function up()
    {
        Schema::create('shift_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // To ensure that the combination of shift_id and user_id is unique
            $table->unique(['shift_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shift_user');
    }
}
