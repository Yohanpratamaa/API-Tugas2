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
        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)->unique();
            $table->string('location', 100);
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->string('unit', 50);
            $table->integer('minimum');
            $table->string('item_status')->default('Masuk');
            $table->decimal('total_price', 10, 2);
            $table->date('entry_date')->useCurrent();
            $table->date('document_date')->useCurrent();
            $table->date('date_of_manufacture')->nullable();
            $table->date('date_of_expired')->nullable();
            $table->string('source', 100);
            $table->string('category', 100);
            $table->string('condition', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Pastikan ini ada untuk rollback
        });
        Schema::dropIfExists('inventories');
    }
};
