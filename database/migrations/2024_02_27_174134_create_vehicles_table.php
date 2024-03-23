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
            $table->string('name');
            $table->string('plate')->unique();
            //the maximum load in Kg which a vehicle can carry
            $table->float('load')->default(0.0);
            //the amount of fuel a vehicle can consume per 1 KM
            $table->float('fuel')->default(0.0);
            $table->string('fuel_type');
            $table->unsignedBigInteger("group_id");
            $table->foreign("group_id")->on("groups")->references("id");
            //The driver of vehicle
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->on("users")->references("id");
            $table->timestamps();
            $table->softDeletes();
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
