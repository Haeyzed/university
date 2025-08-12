<?php

use App\Models\Department;
use App\Models\Designation;
use App\Models\WorkShiftType;
use App\Models\Province;
use App\Models\District;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Foreign keys with model references
            $table->foreignIdFor(Department::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Designation::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(WorkShiftType::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Province::class, 'present_province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignIdFor(District::class, 'present_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignIdFor(Province::class, 'permanent_province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignIdFor(District::class, 'permanent_district_id')->nullable()->constrained('districts')->nullOnDelete();

            $table->string('staff_id')->nullable()->unique();
            $table->string('first_name');
            $table->string('last_name');

            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->text('father_photo')->nullable();
            $table->text('mother_photo')->nullable();

            $table->string('country')->nullable();
            $table->text('present_village')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_village')->nullable();
            $table->text('permanent_address')->nullable();

            $table->tinyInteger('gender')->nullable()->comment('1: Male, 2: Female, 3: Other');
            $table->date('dob');
            $table->date('joining_date')->nullable();
            $table->date('ending_date')->nullable();

            $table->string('email')->unique();
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

            $table->string('education_level')->nullable();
            $table->string('graduation_academy')->nullable();
            $table->string('year_of_graduation')->nullable();
            $table->string('graduation_field')->nullable();

            $table->longText('experience')->nullable();
            $table->longText('note')->nullable();

            $table->double('basic_salary', 10, 2)->default(0.00);
            $table->tinyInteger('contract_type')->default(1)->comment('1 Full Time, 2 Part Time');
            $table->tinyInteger('salary_type')->default(1)->comment('1 Fixed, 2 Hourly');

            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_brach')->nullable(); // Consider renaming to `bank_branch`
            $table->string('tin_no')->nullable();

            $table->text('photo')->nullable();
            $table->text('signature')->nullable();
            $table->text('resume')->nullable();
            $table->text('joining_letter')->nullable();
            $table->text('epf_no')->nullable();

            $table->string('password');
            $table->longText('password_text')->nullable();

            $table->boolean('is_admin')->default(false);
            $table->boolean('login')->default(true);
            $table->tinyInteger('status')->default(1)->comment('0 Inactive, 1 Active');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
