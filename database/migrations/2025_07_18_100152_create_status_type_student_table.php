<?php

use App\Models\StatusType;
use App\Models\Student;
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
        Schema::create('status_type_student', function (Blueprint $table) {
            $table->foreignIdFor(StatusType::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Student::class)
                ->constrained()
                ->cascadeOnDelete();

            // Optionally enforce uniqueness
            $table->unique(['status_type_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_type_student');
    }
};
