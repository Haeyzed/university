<?php

use App\Models\StudentEnroll;
use App\Models\Subject;
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
        Schema::create('enroll_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(StudentEnroll::class)
                ->constrained('student_enrolls')
                ->cascadeOnDelete();

            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class,'teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->decimal('credit_hour', 5, 2)->nullable();
            $table->decimal('final_marks', 5, 2)->nullable();
            $table->decimal('grade_point', 5, 2)->nullable();
            $table->string('grade_letter')->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enroll_subjects');
    }
};
