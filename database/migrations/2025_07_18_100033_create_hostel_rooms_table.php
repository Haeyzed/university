<?php

use App\Models\Hostel;
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
        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignIdFor(Hostel::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('room_type_id')
                ->constrained('hostel_room_types')
                ->cascadeOnDelete();

            // Room details
            $table->string('room_no')->unique();
            $table->unsignedInteger('number_of_bed')->nullable();
            $table->decimal('cost_per_bed', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_rooms');
    }
};
