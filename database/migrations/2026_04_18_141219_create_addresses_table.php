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
       Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // relation
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // label (Home, Office, etc)
            $table->string('label')->nullable();

            // receiver
            $table->string('recipient_name');
            $table->string('phone_number');

            // main address
            $table->text('full_address');

            // region detail
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();

            // note
            $table->text('note')->nullable();

            // coordinate (Leaflet)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // default address
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};