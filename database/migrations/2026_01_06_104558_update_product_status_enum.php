<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE products
            MODIFY status ENUM('active','inactive','sold')
            NOT NULL DEFAULT 'active'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE products
            MODIFY status ENUM('active','inactive')
            NOT NULL DEFAULT 'active'
        ");
    }
};
