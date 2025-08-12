<?php

use App\Models\ExamType;
use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Session;
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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ExamType::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Program::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Session::class, 'academic_session_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Semester::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Section::class, 'section_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->longText('description')->nullable();
            $table->date('exam_date');
            $table->boolean('is_active')->default(true);

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
