<?php

use App\Models\Program;
use App\Models\Subject;
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
        Schema::create('program_subject', function (Blueprint $table) {
            $table->foreignIdFor(Program::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['program_id', 'subject_id'], 'program_subject_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_subject');
    }
};
