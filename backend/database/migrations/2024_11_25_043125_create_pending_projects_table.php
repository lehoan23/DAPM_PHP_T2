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
        Schema::create('pending_projects', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('goal_amount', 18, 2);
            $table->decimal('collected_amount', 18, 2)->default(0);
            $table->enum('status', ['Active', 'Pending', 'Canceled']);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('create_by')->constrained('users');
            $table->foreignId('cate_id')->constrained('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_projects');
    }
};