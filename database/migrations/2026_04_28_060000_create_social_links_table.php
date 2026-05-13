<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->string('platform', 80);
            $table->string('label')->nullable();
            $table->string('url', 2048);
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
