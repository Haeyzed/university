<?php

use App\Models\Faculty;
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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            // Related entities
            $table->foreignIdfor(Faculty::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdfor(Program::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdfor(Session::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdfor(Semester::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdfor(Section::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdfor(Subject::class)->constrained()->cascadeOnDelete();
            $table->foreignIdfor(User::class, 'assigned_by')->constrained('users')->cascadeOnDelete();

            // Assignment details
            $table->string('title');
            $table->longText('description')->nullable();
            $table->decimal('total_marks', 5, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('attach')->nullable();

            // Status & timestamps
            $table->boolean('status')->default(true)->comment('1=Active, 0=Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
