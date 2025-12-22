<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // UUID sebagai identifier UAP
            $table->uuid('uuid')->nullable()->after('id');

            // Relasi ke tabel customers
            $table->foreignId('customer_id')
                  ->nullable()
                  ->after('uuid')
                  ->constrained('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['uuid', 'customer_id']);
        });
    }
};
