<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('displays', function (Blueprint $table) {
            $table->boolean('image_1_is_active')->default(true)->after('image_1_link');
            $table->boolean('image_2_is_active')->default(true)->after('image_2_link');
            $table->boolean('image_3_is_active')->default(true)->after('image_3_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('displays', function (Blueprint $table) {
            //
        });
    }
};
