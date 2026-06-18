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
        Schema::table('categories', function (Blueprint $table) {
            // Jika di tabelmu tidak ada kolom 'image', hapus bagian ->after('image') 
            // agar tidak menyebabkan error lain.
            $table->boolean('is_featured_home')->default(false);
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_featured_home');
        });
    }
};
