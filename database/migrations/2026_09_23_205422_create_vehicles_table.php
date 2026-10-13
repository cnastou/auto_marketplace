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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('make');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('vin')->unique();

            $table->string('pickup_location');
            $table->string('delivery_location');

            $table->enum('status', [
                'available',
                'assigned',
                'in_transit',
                'delivered',
                'cancelled',
            ])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
