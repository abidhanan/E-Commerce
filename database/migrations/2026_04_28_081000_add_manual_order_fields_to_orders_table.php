<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->decimal('subtotal', 15, 2)->default(0)->after('address_id');
            $table->decimal('shipping_cost', 15, 2)->nullable()->after('subtotal');
            $table->string('payment_url')->nullable()->after('snap_token');
            $table->text('customer_note')->nullable()->after('payment_url');
            $table->text('admin_note')->nullable()->after('customer_note');
            $table->timestamp('quoted_at')->nullable()->after('admin_note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn([
                'address_id',
                'subtotal',
                'shipping_cost',
                'payment_url',
                'customer_note',
                'admin_note',
                'quoted_at',
            ]);
        });
    }
};
