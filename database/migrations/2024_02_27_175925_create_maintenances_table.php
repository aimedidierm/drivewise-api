<?php

use App\Enums\MaintenanceTimeUnit;
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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('notification');
            $table->integer('interval')->default(1);
            $table->string('unit')->default(MaintenanceTimeUnit::HOUR->value);
            $table->unsignedBigInteger("vehicle_id");
            $table->foreign("vehicle_id")->on("vehicles")->references("id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
