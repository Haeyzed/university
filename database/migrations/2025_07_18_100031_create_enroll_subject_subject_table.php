<?php

use App\Models\EnrollSubject;
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
        Schema::create('enroll_subject_subject', function (Blueprint $table) {
            $table->foreignIdFor(EnrollSubject::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enroll_subject_subject');
    }
};
