<?php

use App\Models\LeaveType;
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
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignIdFor(LeaveType::class,'type_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users')->nullOnDelete();

            // Main Columns
            $table->date('from_date');
            $table->date('to_date');
            $table->longText('reason')->nullable();
            $table->text('attach')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 = Rejected, 1 = Pending, 2 = Approved');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_leaves');
    }
};
