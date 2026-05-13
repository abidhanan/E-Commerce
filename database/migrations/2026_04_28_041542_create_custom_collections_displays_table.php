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
        Schema::create('custom_collections_displays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete()
                ->nullable();
            $table->string('collection_id')->nullable(); // nama koleksi khusus, opsional
            $table->integer('position')->default(0); // urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_collections_displays');
    }
};