<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('collection_id')
                  ->nullable();
            $table->foreignId('size_guide_id')
                  ->nullable();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->string('material', 100)->nullable();
            $table->enum('gender', ['pria','wanita','unisex'])->default('unisex');
            $table->integer('weight')->default(0);
            $table->integer('temperature')->default(0);
            $table->enum('intensity',['low','high'])->default('low');
            $table->integer('insulation')->default(0);
            $table->integer('breathability')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};