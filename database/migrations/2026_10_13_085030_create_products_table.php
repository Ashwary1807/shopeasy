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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('category')->default('General');
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->string('status')->default('active'); // active, inactive, out_of_stock
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->integer('reviews_count')->default(0);
            $table->string('badge')->nullable(); // e.g. Best Seller, Hot Deal, New Arrival
            $table->string('badge_color')->nullable(); // e.g. bg-amber-500, bg-rose-500
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
