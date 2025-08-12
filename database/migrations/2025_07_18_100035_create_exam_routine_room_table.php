<?php

use App\Models\ClassRoom;
use App\Models\ExamRoutine;
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
        Schema::create('exam_routine_room', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ExamRoutine::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(ClassRoom::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(['exam_routine_id', 'class_room_id'], 'exam_routine_room_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_routine_room');
    }
};
