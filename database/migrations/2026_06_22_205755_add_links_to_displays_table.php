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
            $table->string('image_1_link')->nullable()->after('image_1_sub_title');
            $table->string('image_2_link')->nullable()->after('image_2_sub_title');
            $table->string('image_3_link')->nullable()->after('image_3_sub_title');
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
