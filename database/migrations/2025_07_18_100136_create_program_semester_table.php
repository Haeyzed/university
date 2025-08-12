<?php

use App\Models\Program;
use App\Models\Semester;
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
        Schema::create('program_semester', function (Blueprint $table) {
            $table->foreignIdFor(Program::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Semester::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['program_id', 'semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_semester');
    }
};
