<?php

use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Session;
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
        Schema::create('class_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Subject::class, 'subject_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClassRoom::class, 'room_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Session::class, 'session_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Program::class, 'program_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Semester::class, 'semester_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Section::class, 'section_id')->constrained()->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('day')->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_routines');
    }
};
