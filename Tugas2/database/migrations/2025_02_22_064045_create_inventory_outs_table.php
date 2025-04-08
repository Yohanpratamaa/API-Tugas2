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
        Schema::create('inventory_outs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->string('destination', 100);
            $table->decimal('unit_price', 10, 2);
            $table->date('drop_out_date');
            $table->integer('quantity');
            $table->string('item_status')->default('Keluar');
            $table->string('category', 100)->default('Uncategorized'); // Tambahkan default value
            $table->string('document')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_outs');
    }
};
