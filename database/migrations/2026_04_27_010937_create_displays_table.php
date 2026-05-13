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
        Schema::create('displays', function (Blueprint $table) {
            $table->id();

            // IMAGE 1
            $table->string('image_1_path')->nullable();
            $table->string('image_1_title')->nullable();
            $table->string('image_1_sub_title')->nullable();

            // IMAGE 2
            $table->string('image_2_path')->nullable();
            $table->string('image_2_title')->nullable();


            // IMAGE 3
            $table->string('image_3_path')->nullable();
            $table->string('image_3_title')->nullable();


            // RUNNING TEXT (misal marquee / banner text)
            $table->text('running_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('displays');
    }
};