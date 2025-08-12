<?php

use App\Models\ClassRoom;
use App\Models\Program;
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
        Schema::create('program_class_room', function (Blueprint $table) {
            $table->foreignIdFor(Program::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(ClassRoom::class, 'class_room_id')
                ->constrained()
                ->cascadeOnDelete();

            // Composite Primary Key
            $table->primary(['program_id', 'class_room_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_class_room');
    }
};
