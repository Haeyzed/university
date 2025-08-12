<?php

use App\Models\TransportRoute;
use App\Models\TransportVehicle;
use App\Models\User;
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
        Schema::create('transport_members', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(TransportRoute::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(TransportVehicle::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->date('join_date');
            $table->date('end_date')->nullable();

            $table->boolean('status')->default(true);

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_members');
    }
};
