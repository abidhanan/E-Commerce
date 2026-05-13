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
        Schema::create('size_guides', function (Blueprint $table) {
            $table->id();

            // kategori: shirt, pants, bag, dll
            $table->string('type');

            // optional: nama guide (biar bisa multiple per type)
            $table->string('name')->nullable();

            // optional: gambar
            $table->string('img')->nullable();
            
            // isi json ukuran
            $table->json('data');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_guides');
    }
};