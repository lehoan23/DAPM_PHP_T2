<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id()->autoIncrement();
        $table->string('username');
        $table->string('email')->unique();
        $table->string('phone_number', 15)->nullable();
        $table->string('password');
        $table->string('address')->nullable();
        $table->foreignId('role_id')->constrained('roles');
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