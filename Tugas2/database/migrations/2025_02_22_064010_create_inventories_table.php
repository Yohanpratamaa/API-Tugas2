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
            $table->integer('quantity');
            $table->string('unit', 50);
            $table->string('item_status')->default('Masuk');
            $table->date('entry_date')->useCurrent();
            $table->string('category', 100);
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
