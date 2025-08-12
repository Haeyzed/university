<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    User, Program, Session, Semester, Section, Department,
    Designation, Faculty, Batch, Province, District
};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->unique()->constrained()->cascadeOnDelete();
            $table->string('student_id')->unique();

            // Academic/organizational foreign keys
            $table->foreignIdFor(Program::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Session::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Semester::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Section::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Department::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Designation::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Faculty::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Batch::class)->nullable()->constrained()->nullOnDelete();

            // Location (current & permanent)
            $table->foreignIdFor(Province::class, 'current_province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignIdFor(District::class, 'current_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->text('current_village')->nullable();
            $table->text('current_address')->nullable();
            $table->foreignIdFor(Province::class, 'permanent_province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignIdFor(District::class, 'permanent_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->text('permanent_village')->nullable();
            $table->text('permanent_address')->nullable();

            // Personal info
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->text('father_photo')->nullable();
            $table->text('mother_photo')->nullable();
            $table->tinyInteger('gender')->comment('1: Male, 2: Female, 3: Other');
            $table->date('dob');
            $table->string('phone')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->tinyInteger('marital_status')->nullable();
            $table->tinyInteger('blood_group')->nullable();
            $table->string('nationality')->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_no')->nullable();

            // Education (school)
            $table->text('school_name')->nullable();
            $table->string('school_exam_id')->nullable();
            $table->string('school_graduation_field')->nullable();
            $table->string('school_graduation_year')->nullable();
            $table->string('school_graduation_point')->nullable();
            $table->string('school_transcript')->nullable();
            $table->string('school_certificate')->nullable();

            // Education (college)
            $table->text('collage_name')->nullable();
            $table->string('collage_exam_id')->nullable();
            $table->string('collage_graduation_field')->nullable();
            $table->string('collage_graduation_year')->nullable();
            $table->string('collage_graduation_point')->nullable();
            $table->string('collage_transcript')->nullable();
            $table->string('collage_certificate')->nullable();

            $table->text('photo')->nullable();
            $table->text('signature')->nullable();
            $table->boolean('status')->default(true);

            // Auditing
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
