<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('size', 20)->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};