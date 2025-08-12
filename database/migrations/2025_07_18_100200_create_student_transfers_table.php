<?php

use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
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
        Schema::create('student_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete();

            $table->foreignIdFor(Program::class,'from_program_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Session::class, 'from_session_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Semester::class, 'from_semester_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Section::class, 'from_section_id')->constrained()->cascadeOnDelete();

            $table->foreignIdFor(Program::class, 'to_program_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Session::class, 'to_session_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Semester::class, 'to_semester_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Section::class, 'to_section_id')->constrained()->cascadeOnDelete();

            $table->date('transfer_date');
            $table->longText('note')->nullable();
            $table->boolean('status')->default(true);

            $table->foreignIdFor(User::class,'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_transfers');
    }
};
