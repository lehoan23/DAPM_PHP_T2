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
        Schema::table('pending_projects', function (Blueprint $table) {
            //
            $table->timestamp('deleted_at')->nullable(); // Thêm cột deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_projects', function (Blueprint $table) {
            //
            $table->dropColumn('deleted_at'); // Xóa cột nếu rollback
        });
    }
};