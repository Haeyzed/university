<?php

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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Registration & Program Info
            $table->string('registration_no')->nullable()->index();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->date('apply_date')->nullable();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->tinyInteger('gender')->comment('1=Male, 2=Female, 3=Other');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->tinyInteger('marital_status')->nullable()->comment('1=Single, 2=Married');
            $table->tinyInteger('blood_group')->nullable()->comment('1=A+, 2=A-, 3=B+, 4=B-, 5=O+, 6=O-, 7=AB+, 8=AB-');
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('nationality')->nullable();
            $table->string('national_id')->nullable()->index();
            $table->string('passport_no')->nullable();

            // Family Information
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->text('father_photo')->nullable();
            $table->text('mother_photo')->nullable();

            // Present Address
            $table->string('country')->nullable();
            $table->foreignId('present_province')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('present_district')->nullable()->constrained('districts')->nullOnDelete();
            $table->text('present_village')->nullable();
            $table->text('present_address')->nullable();

            // Permanent Address
            $table->foreignId('permanent_province')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('permanent_district')->nullable()->constrained('districts')->nullOnDelete();
            $table->text('permanent_village')->nullable();
            $table->text('permanent_address')->nullable();

            // Education - School
            $table->text('school_name')->nullable();
            $table->string('school_exam_id')->nullable();
            $table->string('school_graduation_field')->nullable();
            $table->string('school_graduation_year')->nullable();
            $table->string('school_graduation_point')->nullable();
            $table->string('school_transcript')->nullable();
            $table->string('school_certificate')->nullable();

            // Education - College
            $table->text('college_name')->nullable();
            $table->string('college_exam_id')->nullable();
            $table->string('college_graduation_field')->nullable();
            $table->string('college_graduation_year')->nullable();
            $table->string('college_graduation_point')->nullable();
            $table->string('college_transcript')->nullable();
            $table->string('college_certificate')->nullable();

            // Media
            $table->text('photo')->nullable();
            $table->text('signature')->nullable();

            // Payment
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->tinyInteger('pay_status')->default(0)->comment('0=Unpaid, 1=Paid, 2=Cancelled');
            $table->tinyInteger('payment_method')->nullable()->comment('1=Cash, 2=Bank Transfer, 3=Online');

            // Status and Audit
            $table->tinyInteger('status')->default(1)->comment('0=Rejected, 1=Pending, 2=Approved');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
