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
        Schema::create('fuel_vehicles', function (Blueprint $table) {
            $table->id();
            $table->float('volume')->default(0.0);
            $table->float('total')->default(0.0);
            $table->unsignedBigInteger("vehicle_id");
            $table->foreign("vehicle_id")->on("vehicles")->references("id");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->on("users")->references("id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_vehicles');
    }
};
