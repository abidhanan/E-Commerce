<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_gateway')) {
                $table->string('payment_gateway', 30)->nullable()->after('stock_deducted_at');
            }

            if (! Schema::hasColumn('orders', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_gateway')->index();
            }

            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('payment_reference');
            }

            if (! Schema::hasColumn('orders', 'payment_url')) {
                $table->string('payment_url')->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 50)->nullable()->after('payment_url')->index();
            }

            if (! Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }

            if (! Schema::hasColumn('orders', 'callback_payload')) {
                $table->json('callback_payload')->nullable()->after('paid_at');
            }
        });

        if (Schema::hasColumn('orders', 'snap_token')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('snap_token');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('status');
            }

            $table->dropColumn([
                'payment_gateway',
                'payment_reference',
                'payment_method',
                'payment_status',
                'paid_at',
                'callback_payload',
            ]);
        });
    }
};
