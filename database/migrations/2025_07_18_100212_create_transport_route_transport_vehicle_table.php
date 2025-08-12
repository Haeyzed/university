<?php

use App\Models\TransportRoute;
use App\Models\TransportVehicle;
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
        Schema::create('transport_route_transport_vehicle', function (Blueprint $table) {
            $table->foreignIdFor(TransportRoute::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(TransportVehicle::class)->constrained()->cascadeOnDelete();

            $table->primary(['transport_route_id', 'transport_vehicle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_route_transport_vehicle');
    }
};
