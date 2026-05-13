<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('shipped_at')->nullable()->after('quoted_at');
            $table->timestamp('delivery_estimated_at')->nullable()->after('shipped_at');
            $table->timestamp('completed_at')->nullable()->after('delivery_estimated_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipped_at',
                'delivery_estimated_at',
                'completed_at',
            ]);
        });
    }
};
